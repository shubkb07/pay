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
     * String to encrypt and decrypt without external IV or key.
     * Creates URL-safe strings for payment links.
     *
     * @param string $action 'encrypt' or 'decrypt'
     * @param string $string String to process
     *
     * @return string Processed string
     */
    private function encrypt_decrypt($action, $string) {
        // Use a fixed salt - ensure it's not empty
        $salt = !empty($this->salt) ? $this->salt : 'PayuDefaultSalt2023';

        if ($action == 'encrypt') {
            if (empty($string)) {
                return '';
            }
            
            // Step 1: Add the salt to the string
            $salted = $string . '|' . $salt;

            // Step 2: Base64 encode
            $encoded = base64_encode($salted);

            // Step 3: Replace URL-unsafe characters
            $encoded = str_replace(['+', '/', '='], ['p', 's', 'e'], $encoded);

            // Step 4: Reverse the string for additional obfuscation
            $result = strrev($encoded);
            
            return $result;
        } elseif ($action == 'decrypt') {
            if (empty($string)) {
                return '';
            }
            
            // Step 1: Reverse the string back
            $reversed = strrev($string);

            // Step 2: Replace the special characters back
            $base64 = str_replace(['p', 's', 'e'], ['+', '/', '='], $reversed);

            // Step 3: Base64 decode
            $decoded = base64_decode($base64);
            if ($decoded === false) {
                error_log('Failed to base64 decode the string');
                return '';
            }

            // Step 4: Remove the salt
            $parts = explode('|', $decoded);
            if (count($parts) >= 2) {
                $last = array_pop($parts);
                if ($last === $salt) {
                    return implode('|', $parts);
                } else {
                    error_log('Salt verification failed');
                }
            } else {
                error_log('Invalid format: no separator found');
            }
            
            return '';
        }
        
        return '';
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
