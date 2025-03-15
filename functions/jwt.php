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
 */
function jwt_create_token($data) {
    include_once INC . 'lib/php-jwt/src/JWT.php';
    $key = 'secret';
    return JWT::encode($data, $key, 'HS256');
}

/**
 * JWT Decode Token.
 */
function jwt_decode_token($token) {
    include_once INC . 'lib/php-jwt/src/JWT.php';
    include_once INC . 'lib/php-jwt/src/Key.php';
    $key = 'secret';
    return json_decode(json_encode(JWT::decode($token, new Key($key, 'HS256'))), true);
}
