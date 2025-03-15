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
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    // Convert the object into an associative array.
    $decoded = json_decode(json_encode($decoded), true);
    return $decoded;
}

echo '<br>';
echo 'random 4';
echo '<br>';
$text = array('name' => 'Some Name', 'email' => 'example@example.com');
$token = jwt_create_token($text);
echo '<br>';
var_dump($token);
echo '<br>';
echo '<pre>';
print_r(jwt_decode_token($token));
echo '</pre>';
echo '<br>';
