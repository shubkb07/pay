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
     * Salt.
     */
    private $salt;

    /**
     * Const.
     */
    public function __construct() {
        $this->client_id = get_option('payu_client_id');
        $this->client_secret = get_option('payu_client_secret');
        $this->mid = get_option('payu_mid');
        $this->salt = get_option('payu_salt_32');
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
        $key        = $salt; // Using salt as key. For improved security, consider a stronger key derivation.
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
     * Create Payment Link.
     */
    public function create_pay_link() {
        $txnid = $this->create_transaction_id();
        echo 'txnid: ' . $txnid . '<br>';
        $encrypted_txnid = $this->encrypt_decrypt('encrypt', $txnid);
        echo 'encrypted_txnid: ' . $encrypted_txnid . '<br>';
        $decrypted_txnid = $this->encrypt_decrypt('decrypt', $encrypted_txnid);
        echo 'decrypted_txnid: ' . $decrypted_txnid . '<br>';
    }
}
