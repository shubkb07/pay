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
        // Use a fixed salt - this isn't cryptographically secure.
        // but good enough for URL obfuscation.
        $salt = $this->salt;

        if ($action == 'encrypt') {
            // Step 1: Add the salt to the string.
            $salted = $string . '|' . $salt;

            // Step 2: Base64 encode.
            $encoded = base64_encode($salted);

            // Step 3: Replace URL-unsafe characters.
            $encoded = str_replace(['+', '/', '='], ['p', 's', 'e'], $encoded);

            // Step 4: Reverse the string for additional obfuscation.
            $reversed = strrev($encoded);

            // Step 5: Convert to hex and make more readable.
            $result = '';
            for ($i = 0; $i < strlen($reversed); $i++) {
                $char = $reversed[$i];
                // Only encode some characters to keep length reasonable.
                if (rand(0, 2) > 0) {
                    $result .= $char;
                } else {
                    $result .= dechex(ord($char));
                }
            }

            return $result;
        } elseif ($action == 'decrypt') {
            // Step 1: Decode the hex characters.
            $dehexed = '';
            $i = 0;
            while ($i < strlen($string)) {
                // If current char is a-f or 0-9, check if it's part of a hex pair.
                if (ctype_xdigit($string[$i]) && isset($string[ $i + 1 ]) && ctype_xdigit($string[ $i + 1 ])) {
                    // Try to interpret as hex.
                    $hex = $string[$i] . $string[ $i + 1 ];
                    $char = chr(hexdec($hex));
                    // Check if this is a valid character.
                    if (preg_match('/[a-zA-Z0-9psep\/\+\=]/', $char)) {
                        $dehexed .= $char;
                        $i += 2;
                        continue;
                    }
                }
                // Not a hex pair or not a valid char, treat as regular char.
                $dehexed .= $string[$i];
                $i++;
            }

            // Step 2: Reverse the string back.
            $reversed_back = strrev($dehexed);

            // Step 3: Replace the special characters back.
            $base64 = str_replace(['p', 's', 'e'], ['+', '/', '='], $reversed_back);

            // Step 4: Base64 decode.
            $decoded = base64_decode($base64);

            // Step 5: Remove the salt.
            $parts = explode('|', $decoded);
            if (count($parts) >= 2 && end($parts) === $salt) {
                array_pop($parts);
                return implode('|', $parts);
            }

            // If we can't verify the salt, return empty string or throw an error.
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
