<?php

/**
 * User Class.
 */

namespace Pay;

/**
 * User Class.
 */
class User {

    /**
     * DB.
     */
    private $db;

    /**
     * Email.
     */
    public $email;

    /**
     * User ID.
     */
    public $user_id;

    /**
     * Is User Banned.
     */
    public $is_blocked;

    /**
     * User Data.
     */
    public $user_data;

    /**
     * User Exists.
     */
    public $exists;

    /**
     * Constructor.
     *
     * @param string $email Email.
     */
    public function __construct($email) {
        global $db;
        $this->db = $db;
        $this->email = $email;

        // Fetch user data.
        $this->user_data = $this->fetch($email);
    }

    /**
     * Fetch User Data.
     *
     * @param string $email Email.
     *
     * @return array|null User data or null if not found.
     */
    private function fetch($email) {
        // Fetch user data from the database.
        $users = $this->db->select_data('users', ['*'], ['email' => $email]);

        echo 'User Data: ';
        var_dump($users);
        if (!empty($users) && isset($users[0])) {
            $user_data = $users[0];
            $this->is_blocked = $user_data['account_status'] === 'blocked';
            $this->exists = true;
            return $user_data;
        } else {
            echo 'User not found';
            $this->create($email);
            return $this->fetch($email);
        }

        $this->exists = false;
        return null;
    }

    /**
     * Get User ID.
     *
     * @return int|null User ID or null if user doesn't exist.
     */
    public function get_user_id() {
        if (isset($this->user_data) && isset($this->user_data['id'])) {
            return $this->user_data['id'];
        }
        return null;
    }

    /**
     * Create User.
     *
     * @param string $email Email.
     *
     * @return void
     */
    public function create($email) {
        $this->db->insert_data('users', ['email' => $email]);
    }
}
