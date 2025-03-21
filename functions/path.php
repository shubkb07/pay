<?php

/**
 * Path Declaration.
 */

$path = explode('/', $_GET['path'] ?? '');

// If last element is empty, remove it.
if (end($path) === '') {
    array_pop($path);
}

/*
 * Single Path.
 */

if (count($path) > 0) {
    if ($path[0] === 'api') {
        $invalid_endpoint = json_encode(array('error' => 'Invalid API Endpoint'));
        header('Content-Type: application/json; charset=utf-8');
        if (count($path) === 4 && $path[1] === 'v1') {
            if ($path[2] === 'webhook') {
            } elseif ($path[2] === 'pay') {
            } elseif ($path[2] === 'cron') {
                if ($path[3] === 'daily') {
                    require_once INC . 'functions/cron.php';
                    daily_cron();
                    echo json_encode(array('success' => 'Daily Cron Job'));
                } elseif ($path[3] === 'hourly') {
                    echo json_encode(array('success' => 'Hourly Cron Job'));
                } elseif ($path[3] === 'minutely') {
                    echo json_encode(array('success' => 'Minutely Cron Job'));
                } else {
                    echo $invalid_endpoint;
                }
            } elseif ($path[2] === 'jwt') {
                if ($path[3] === 'create') {
                    $body = json_decode(file_get_contents('php://input'), true);
                    echo json_encode(array('token' => jwt_create_token($body['data'])));
                } elseif ($path[3] === 'verify') {
                    $body = json_decode(file_get_contents('php://input'), true);
                    echo json_encode(array('verify' => jwt_verify_token($body['token'], $body['data'])));
                } else {
                    echo $invalid_endpoint;
                }
            } else {
                echo $invalid_endpoint;
            }
        }
    } elseif ($path[0] === 'pay') {
        header('Content-Type: text/html; charset=utf-8');
        if (count($path) === 3) {
            $pay_id = $path[2];
            if ($path[1] === 'success') {
                include_once ASSETS . 'pages/success.php';
            } elseif ($path[1] === 'failed') {
                include_once ASSETS . 'pages/failed.php';
            } elseif ($path[1] === 'c') {
                include_once ASSETS . 'pages/create.php';
            } elseif ($path[1] === 'p') {
                if (isset($_GET['qr'])) {
                    // QR to current page without search params.
                    header('Content-Type: image/png');
                    echo qr_code(
                        (
                            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://'
                        )
                        . $_SERVER['HTTP_HOST']
                        . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
                    );
                } else {
                    include_once ASSETS . 'pages/pay.php';
                }
            } elseif ($path[1] === 'o') {
                $pay->redirect_pay($pay_id);
            } else {
                include_once ASSETS . 'pages/404.php';
            }
        } else {
            include_once ASSETS . 'pages/404.php';
        }
    } elseif (count($path) === 1) {
        header('Content-Type: text/html; charset=utf-8');
        if ($path[0] === 'ct') {
            echo '<pre>';
            print_r(create_tables());
            echo '</pre>';
        } elseif ($path[0] === 'p') {
            $user = array(
                     'name'  => 'Shubham Kumar Bansal',
                     'email' => 'shub@shubkb.com',
                     'phone' => '+1 1234567890',
                    );
            $address = array(
                        'address' => '123, Example Street',
                        'city'    => 'Example City',
                        'state'   => 'Example State',
                        'zip'     => '123456',
                        'country' => 'Example Country',
                       );
            echo '<pre>';
            print_r($pay);
            echo '</pre>';
            echo '<pre>';
            print_r($pay->create_pay_link($user, $address, 1));
            echo '</pre>';
        } else {
            include_once ASSETS . 'pages/404.php';
        }
    } else {
        include_once ASSETS . 'pages/404.php';
    }
} elseif (count($path) === 0) {
    header('Content-Type: text/html; charset=utf-8');
    include_once ASSETS . 'pages/home.php';
}
die();
