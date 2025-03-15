<?php
/**
 * JWT.
 *
 * @package  Pay
 * @author   Shubham Kumar Bansal <
 */

use Firebase\JWT\{JWT, key, BeforeValidException, ExpiredException, SignatureInvalidException};

/**
 * Include all necessary JWT files.
 */
function jwt_include_files() {
    include_once INC . 'lib/php-jwt/src/JWT.php';
    include_once INC . 'lib/php-jwt/src/Key.php';
    include_once INC . 'lib/php-jwt/src/BeforeValidException.php';
    include_once INC . 'lib/php-jwt/src/ExpiredException.php';
    include_once INC . 'lib/php-jwt/src/SignatureInvalidException.php';
}

/**
 * JWT Create Token.
 *
 * @param array $data Data to encode.
 *
 * @return string|false Encoded token or false on error.
 */
function jwt_create_token($data) {
    try {
        jwt_include_files();
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
        jwt_include_files();
        $key = 'secret';
        return json_decode(json_encode(JWT::decode($token, new Key($key, 'HS256'))), true);
    } catch (SignatureInvalidException $e) {
        error_log('JWT Invalid Signature: ' . $e->getMessage());
        return false;
    } catch (BeforeValidException $e) {
        error_log('JWT Not Valid Yet: ' . $e->getMessage());
        return false;
    } catch (ExpiredException $e) {
        error_log('JWT Expired: ' . $e->getMessage());
        return false;
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
        jwt_include_files();
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
