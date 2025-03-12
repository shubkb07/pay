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
            if (!empty($bearer_data['access_token']) && isset($bearer_data['expire_in']) && $bearer_data['expire_in'] > $current_time) {
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
            $expiration_time = $current_time + $expires_in;
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
     * Create Payment Link.
     */
    public function create_pay_link() {
        // Get Bearer Token.
        $access_token = $this->get_bearer();
        echo 'access_token: ' . $access_token . '<br>';
        $txnid = $this->create_transaction_id();
        echo 'txnid: ' . $txnid . '<br>';
        $encrypted_txnid = $this->encrypt_decrypt('encrypt', $txnid);
        echo 'encrypted_txnid: ' . $encrypted_txnid . '<br>';
        $decrypted_txnid = $this->encrypt_decrypt('decrypt', $encrypted_txnid);
        echo 'decrypted_txnid: ' . $decrypted_txnid . '<br>';
    }
}
