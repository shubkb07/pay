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
    include_once INC . 'lib/firebase/php-jwt/src/JWT.php';
    $key = 'secret';
    return JWT::encode($data, $key, 'HS256');
}

/**
 * JWT Decode Token.
 */
function jwt_decode_token($token) {
    include_once INC . 'lib/firebase/php-jwt/src/JWT.php';
    include_once INC . 'lib/firebase/php-jwt/src/Key.php';
    $key = 'secret';
    $header = new StdClass();
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'), $headers);
    return [$header, $decoded];
}

$text = 'Hello World!';
$token = jwt_create_token($text);
echo '<br>';
var_dump($token);
echo '<br>';
var_dump(jwt_decode_token($token));
echo '<br>';


/**
 * JWT Verify Token.
 */
function jwt_verify_token($token) {
    $key = 'secret';
    return JWT::decode($token, new Key($key, 'HS256'));
}

