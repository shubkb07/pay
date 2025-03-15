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
 * @return string Encoded token.
 */
function jwt_create_token($data) {
    include_once INC . 'lib/php-jwt/src/JWT.php';
    $key = 'secret';
    return JWT::encode($data, $key, 'HS256');
}

/**
 * JWT Decode Token.
 *
 * @param string $token Token to decode.
 *
 * @return array Decoded data.
 */
function jwt_decode_token($token) {
    include_once INC . 'lib/php-jwt/src/JWT.php';
    include_once INC . 'lib/php-jwt/src/Key.php';
    $key = 'secret';
    return json_decode(json_encode(JWT::decode($token, new Key($key, 'HS256'))), true);
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
    include_once INC . 'lib/php-jwt/src/JWT.php';
    include_once INC . 'lib/php-jwt/src/Key.php';
    $decoded = jwt_decode_token($token);
    $diff = array_diff($decoded, $data);
    return empty($diff);
}
