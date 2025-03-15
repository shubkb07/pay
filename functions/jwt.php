<?php
/**
 * JWT.
 *
 * @package  Pay
 * @author   Shubham Kumar Bansal <
 */

use Firebase\JWT\{JWT, key};

/**
 * JWT Create Token.
 *
 * @param array $data Data to encode.
 *
 * @return string|false Encoded token or false on error.
 */
function jwt_create_token($data) {
    try {
        include_once INC . 'lib/php-jwt/src/JWT.php';
        $key = 'secret';
        return JWT::encode($data, $key, 'HS256');
    } catch (Exception $e) {
        error_log('JWT Create Error: ' . $e->getMessage());
        return false;
    }
}

/**
 * JWT Decode Token.
 *
 * @param string $token Token to decode.
 *
 * @return array|false Decoded data or false on error.
 */
function jwt_decode_token($token) {
    try {
        include_once INC . 'lib/php-jwt/src/JWT.php';
        include_once INC . 'lib/php-jwt/src/Key.php';
        $key = 'secret';
        return json_decode(json_encode(JWT::decode($token, new Key($key, 'HS256'))), true);
    } catch (Exception $e) {
        error_log('JWT Decode Error: ' . $e->getMessage());
        return false;
    }
}

/**
 * JWT Verify Token.
 *
 * @param string $token Token to verify.
 * @param array  $data  Data to verify.
 *
 * @return boolean Verification status.
 */
function jwt_verify_token($token, $data) {
    try {
        include_once INC . 'lib/php-jwt/src/JWT.php';
        include_once INC . 'lib/php-jwt/src/Key.php';
        $decoded = jwt_decode_token($token);

        if ($decoded === false) {
            return false;
        }

        $diff = array_diff($decoded, $data);
        return empty($diff);
    } catch (Exception $e) {
        error_log('JWT Verify Error: ' . $e->getMessage());
        return false;
    }
}
