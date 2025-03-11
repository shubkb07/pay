<?php

/**
 * Path Declaration.
 */

$path = explode('/', $_GET['path'] ?? '');

/*
 * Single Path.
 */

if (count($path) > 0) {
    if ($path[0] === 'api') {
        if (count($path) === 4 && $path[1] === 'v1') {
            if ($path[2] === 'webhook' && $path[3] === 'pay') {
            }
        }
    } elseif (count($path) === 1) {
        header('Content-Type: text/html; charset=utf-8');
        if ($path[0] === 'home' || $path[0] === '') {
            include_once ASSETS . 'pages/home.php';
        } elseif ($path[0] === 's') {
            include_once ASSETS . 'pages/success.php';
        } elseif ($path[0] === 'f') {
            include_once ASSETS . 'pages/failed.php';
        } elseif ($path[0] === 'ct') {
            echo '<pre>';
            print_r(create_tables());
            echo '</pre>';
        } elseif ($path[0] === 'p') {
            echo '<pre>';
            print_r($pay->create_pay_link());
            echo '</pre>';
        } else {
            include_once ASSETS . 'pages/404.php';
        }
    }
}
die();
