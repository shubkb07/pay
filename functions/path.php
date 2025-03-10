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
    var_dump($path);
    if ($path[0] === 'home' || $path[0] === '') {
        var_dump('home');
        include_once ASSETS . 'html/home.php';
    } elseif ($path[0] === 'about') {
        include_once ASSETS . 'html/about.php';
    } elseif ($path[0] === 'contact') {
        include_once ASSETS . 'html/contact.php';
    } else {
        include_once ASSETS . 'html/404.php';
    }
}
die();
