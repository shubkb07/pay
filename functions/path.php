<?php

/**
 * Path Declaration.
 */

$path = explode('/', $_GET['path'] ?? '');

/*
 * Single Path.
 */

if (count($path) === 1) {
    header('Content-Type: text/html; charset=utf-8');
    if ($path[0] === 'home' || $path[0] === '') {
        include_once ASSETS . 'pages/home.php';
    } elseif ($path[0] === 'about') {
        include_once ASSETS . 'pages/about.php';
    } elseif ($path[0] === 'contact') {
        include_once ASSETS . 'pages/contact.php';
    } else {
        include_once ASSETS . 'pages/404.php';
    }
}
die();
