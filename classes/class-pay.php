<?php

/*
 * Pay API.
 */

namespace Pay;

class Pay
{
    /*
     * Client ID.
     */

    private $client_id;

    /*
     * Client Secret.
     */

    private $client_secret;

    /*
     * MID.
     */

    private $mid;

    /*
     * mode.
     */

    private $mode;

    /*
     * Bearer.
     */

    private $bearer;

    /*
     * Salt.
     */

    private $salt;

    /*
     * Key.
     */

    private $key;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->client_id = get_option('payu_client_id');
        $this->client_secret = get_option('payu_client_secret');
        $this->mid = get_option('payu_mid');
        $this->mode = get_option('payu_mode');
        $this->bearer = get_option('payu_bearer');
        $this->salt = get_option('payu_salt_32');
        $this->key = get_option('payu_salt_256');
    }

    /**
     * Create Transaction ID.
     *
     * @return string
     */
    private function create_transaction_id() {
        // Create transaction ID with format: TXN + timestamp + 5-digit random number
        $date = new \DateTime();
        $timestamp = $date->format('YmdHis') . substr(microtime(), 2, 3);
        $random = rand(10000, 99999); // 5-digit random number.
        $txnid = 'TXN' . $timestamp . $random;

        try {
            // Check if DB constants are defined
            if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASS') || !defined('DB_NAME') || !defined('DB_PORT')) {
                // Can't check database, return the generated ID
                return $txnid;
            }
            
            // Make sure to use full namespace path for Db class
            $db = new \Pay\Db();
            $result = $db->select_data('transactions', ['txnid'], ['txnid' => $txnid]);

            // If transaction ID exists, recursively generate a new one.
            if (!empty($result)) {
                return $this->create_transaction_id();
            }
        } catch (\Exception $e) {
            // Log the error but continue with the generated ID
            error_log('Transaction ID verification error: ' . $e->getMessage());
            // In case of DB error, still return the generated ID
        }

        return $txnid;
    }

    /**
     * Encrypt or decrypt a string using a simple XOR cipher.
     * The result is a URL-safe string consisting only of 0-9 and a-z.
     *
     * @param string $action 'encrypt' or 'decrypt'
     * @param string $string String to process
     *
     * @return string Processed string
     * @throws \Exception if invalid action or format is provided.
     */
    private function encrypt_decrypt($action, $string) {
        // Use the instance salt as the key for encryption/decryption.
        $salt       = $this->salt;
        $key        = $this->key;
        $key_length = strlen($key);

        if ($action === 'encrypt') {
            $result = '';
            // Loop through each character of the input string.
            for ($i = 0, $len = strlen($string); $i < $len; $i++) {
                $char     = $string[$i];
                // Cycle through key characters.
                $key_char = $key[$i % $key_length];
                // XOR the ASCII values.
                $xor_value = ord($char) ^ ord($key_char);
                // Convert the XOR result to base36 and pad to 2 characters.
                $base36 = base_convert($xor_value, 10, 36);
                $base36 = str_pad($base36, 2, '0', STR_PAD_LEFT);
                $result .= $base36;
            }
            return $result;
        } elseif ($action === 'decrypt') {
            // The encrypted string should have even length (each byte represented by 2 characters).
            if (strlen($string) % 2 !== 0) {
                throw new \Exception('Invalid encrypted string length.');
            }
            $result = '';
            $chunks = str_split($string, 2);
            $i      = 0;
            // Process each chunk.
            foreach ($chunks as $chunk) {
                // Convert the base36 chunk back to an integer.
                $xor_value = intval(base_convert($chunk, 36, 10));
                $key_char  = $key[$i % $key_length];
                // XOR to get the original character.
                $original_char = chr($xor_value ^ ord($key_char));
                $result       .= $original_char;
                $i++;
            }
            return $result;
        } else {
            throw new \Exception('Invalid action provided. Use "encrypt" or "decrypt".');
        }
    }

    /**
     * Get Bearer Token.
     *
     * Check if bearer is not null, then json parse it.
     * Check, if expire_in is less than in current time, then get new bearer token.
     * For new token request to url,
     * if mode = test then use test url - https://uat-accounts.payu.in,
     * else use production url - https://accounts.payu.in.
     * In this request set body in url encoded format,
     * with client_id, client_secret, grant_type - client_credentials, and scope - create_payment_links.
     * after you will get response, then json parse it and get access_token and expire_in.
     * if expire_in is less than 60 seconds,
     * then no need to change in set in option, just use it.
     * if expire_in is greater than 60 seconds,
     * then in json {access_token, expire_in in time format from current time}
     * and set update_option('payu_bearer', json_encode($json)).
     * and return access_token.
     *
     * @return string
     */
    private function get_bearer() {
        $current_time = time();
        $access_token = '';

        // Check if bearer is not null and parse it.
        if (!empty($this->bearer)) {
            $bearer_data = json_decode($this->bearer, true);

            // Check if token is still valid.
            if (
                !empty($bearer_data['access_token'])
                && isset($bearer_data['expire_in'])
                && $bearer_data['expire_in'] > $current_time
            ) {
                return $bearer_data['access_token'];
            }
        }

        // Token is expired or doesn't exist, get a new one.
        $api_url = ($this->mode === 'test')
            ? 'https://uat-accounts.payu.in/oauth/token'
            : 'https://accounts.payu.in/oauth/token';

        // Prepare the request data.
        $post_data = http_build_query(
            array(
             'client_id'     => $this->client_id,
             'client_secret' => $this->client_secret,
             'grant_type'    => 'client_credentials',
             'scope'         => 'create_payment_links',
            )
        );

        // Initialize cURL session.
        $ch = curl_init($api_url);

        // Set cURL options.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                              'Content-Type: application/x-www-form-urlencoded',
                                              'Content-Length: ' . strlen($post_data),
                                             ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // Execute cURL request.
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL session.
        curl_close($ch);

        // Check for errors.
        if (!$response || $error) {
            error_log('PayU Bearer Token Error: ' . ($error ?: 'Unknown error'));
            return '';
        }

        // Parse the response.
        $api_response = json_decode($response, true);

        if (empty($api_response['access_token']) || empty($api_response['expires_in'])) {
            error_log('PayU Bearer Token Error: Invalid response - ' . $response);
            return '';
        }

        // Get token data.
        $access_token = $api_response['access_token'];
        $expires_in = $api_response['expires_in'];

        // If token expires in more than 60 seconds, save it.
        if ($expires_in > 60) {
            $expiration_time = $current_time + $expires_in - 60;
            $bearer_data = array(
                            'access_token' => $access_token,
                            'expire_in'    => $expiration_time,
                           );

            // Save bearer data to your storage mechanism (replace with your storage method)
            // For example, you might have a function like:
            // $this->save_option('payu_bearer', json_encode($bearer_data)).
            $this->bearer = json_encode($bearer_data);

            // Assuming you have a function to update your options.
            update_option('payu_bearer', json_encode($bearer_data));
        }

        return $access_token;
    }

    /**
     * Get Payment Link from PayU.
     *
     * Make request to url,
     * if mode = test then use test url - https://uatoneapi.payu.in/payment-links,
     * else use production url - https://oneapi.payu.in/payment-links.
     * Set Header mid and Authorization Bearer access_token,
     * and set body raw,
     * in that body pass json stringified data,
     * {
     *   "transactionId": $transaction_id,
     *   "isAmountFilledByCustomer": false,
     *   "customer": $user,
     *   "address": $address,
     *   "subAmount": $sub_amount,
     *   "tax": $tax,
     *   "discount": $discount,
     *   "description": $description,
     *   "source": "API",
     *   "currency": $currency,
     *   "expiryDate": $expire_in,
     *   "successURL": "https://pay.sh6.me/includes/test/",
     *   "failureURL": "https://pay.sh6.me/includes/test/",
     *   "maxPaymentsAllowed": 1
     * }
     * then will get response, then json parse it and get payment link.
     * {
     *  "status": 0,
     *  "message": "PaymentLink generated",
     *  "result": {
     *   "subAmount": 1000,
     *   "tax": 200,
     *   "shippingCharge": 0,
     *   "totalAmount": 900,
     *   "invoiceNumber": "INV1714761498192",
     *   "paymentLink": "https://v.payu.in/PAYUMN/RIMsTvRkOH4k",
     *   "description": "Testing I1",
     *   "active": true,
     *   "isPartialPaymentAllowed": false,
     *   "expiryDate": "2026-06-23 09:07:21",
     *   "udf": {
     *    "udf1": null,
     *    "udf2": null,
     *    "udf3": null,
     *    "udf4": null,
     *    "udf5": null
     *   },
     *   "address": {
     *    "line1": "SN",
     *    "line2": null,
     *    "city": "Baddi",
     *    "state": "HP",
     *    "country": null,
     *    "zipCode": "173205"
     *   },
     *   "emailStatus": "not opted",
     *   "smsStatus": "not opted",
     *   "currency": "INR",
     *   "addedOn": "2025-03-12 21:58:19",
     *   "status": "active",
     *   "maxPaymentsAllowed": 1,
     *   "customerName": "Shubham",
     *   "customerPhone": "+919999999999",
     *   "customerEmail": "shub@shubkb.com",
     *   "notes": null,
     *   "amountCollected": 0,
     *   "dueAmount": 900,
     *   "minAmountForCustomer": 1,
     *   "adjustment": 0,
     *   "discount": 300,
     *   "customParams": null,
     *   "transactionId": "TXN123456"
     *  },
     *  "errorCode": null,
     *  "guid": "b8d46ef3-a131-4c9d-8c96-47dca5dbf1d6"
     * }
     * from this get invoiceNumber, payment link, guid in array and return it.
     *
     * @param string  $transaction_id Transaction ID.
     * @param array   $user           Customer details.
     * @param array   $address        Customer address.
     * @param string  $description    Description.
     * @param string  $currency       Currency.
     * @param integer $sub_amount     Sub amount.
     * @param integer $tax            Tax.
     * @param integer $discount       Discount.
     * @param integer $expire_in      Expiry time.
     *
     * @return array
     */
    private function get_payment_link($transaction_id, $user, $address, $description, $currency, $sub_amount, $tax = 0, $discount = 0, $expire_in = 3600) {
        $access_token = $this->get_bearer();

        // If no access token, return empty array.
        if (empty($access_token)) {
            error_log('PayU Payment Link Error: No access token available');
            return array();
        }

        // Set API URL based on mode.
        $api_url = ($this->mode === 'test')
            ? 'https://uatoneapi.payu.in/payment-links'
            : 'https://oneapi.payu.in/payment-links';

        // Calculate expiry date.
        $expiry_date = date('Y-m-d H:i:s', time() + $expire_in);

        // Encrypt transaction ID.
        $enc_transaction_id = $this->encrypt_decrypt('encrypt', $transaction_id);

        // Prepare request payload.
        $payload = array(
                    'transactionId'            => $transaction_id,
                    'isAmountFilledByCustomer' => false,
                    'customer'                 => $user,
                    'address'                  => $address,
                    'subAmount'                => $sub_amount,
                    'tax'                      => $tax,
                    'discount'                 => $discount,
                    'description'              => $description,
                    'source'                   => 'API',
                    'currency'                 => $currency,
                    'expiryDate'               => $expiry_date,
                    'successURL'               => 'https://pay.sh6.me/pay/success/' . $enc_transaction_id,
                    'failureURL'               => 'https://pay.sh6.me/pay/failed/' . $enc_transaction_id,
                    'maxPaymentsAllowed'       => 1,
                   );

        $json_payload = json_encode($payload);

        // Initialize cURL session.
        $ch = curl_init($api_url);

        // Set cURL options.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                              'Content-Type: application/json',
                                              'Content-Length: ' . strlen($json_payload),
                                              'mid: ' . $this->mid,
                                              'Authorization: Bearer ' . $access_token,
                                             ));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // Execute cURL request.
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL session.
        curl_close($ch);

        // Check for errors.
        if (!$response || $error) {
            error_log('PayU Payment Link Error: ' . ($error ?: 'Unknown error'));
            return array();
        }

        // Parse the response.
        $api_response = json_decode($response, true);

        // Check for API error.
        if (empty($api_response) || isset($api_response['status']) && $api_response['status'] !== 0) {
            $error_message = isset($api_response['message']) ? $api_response['message'] : 'Unknown error';
            error_log('PayU Payment Link Error: ' . $error_message . ' - ' . $response);
            return array();
        }

        // Extract required information.
        $result = array();

        if (!empty($api_response['result'])) {
            $result = array(
                       'invoiceNumber' => $api_response['result']['invoiceNumber'] ?? '',
                       'paymentLink'   => $api_response['result']['paymentLink'] ?? '',
                       'guid'          => $api_response['guid'] ?? '',
                      );
        }

        return $result;
    }

    /**
     * Create Payment Link.
     */
    public function create_pay_link() {
        // Get Bearer Token.
        $txnid = $this->create_transaction_id();
        $payment_link = $this->get_payment_link(
            $txnid,
            array(
             'name'  => 'Shubham',
             'phone' => '+919999999999',
             'email' => 'shub@shubkb.com',
            ),
            array(
             'line1'   => 'SN',
             'city'    => 'Baddi',
             'state'   => 'HP',
             'country' => 'India',
             'zipCode' => '173205',
            ),
            'Testing I1',
            'INR',
            1000,
            200,
            300,
            36000
        );
        echo '<pre>';
        print_r($payment_link);
        echo '</pre>';
        echo 'txnid: ' . $txnid . '<br>';
        $encrypted_txnid = $this->encrypt_decrypt('encrypt', $txnid);
        echo 'encrypted_txnid: ' . $encrypted_txnid . '<br>';
        $decrypted_txnid = $this->encrypt_decrypt('decrypt', $encrypted_txnid);
        echo 'decrypted_txnid: ' . $decrypted_txnid . '<br>';
    }
}
