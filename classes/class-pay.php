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

    /**
     * MID.
     */

    private $mid;

    /**
     * Const.
     */
    public function __construct() {
        $this->client_id = get_option('payu_client_id');
        $this->client_secret = get_option('payu_client_secret');
        $this->mid = get_option('payu_mid');
    }

    /**
     * Create Transaction ID.
     *
     * @return string
     */
    public function create_transaction_id() {
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
}
