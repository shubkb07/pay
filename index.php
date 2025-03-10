<?php

/**
 * This is the main entry point for the Pay application.
 *
 * PHP version 8.0.0
 *
 * @category pay
 * @package  Pay
 * @author   Shubham Kumar Bansal <shub@shubkb.com>
 * @license  MIT License
 * @version  1.0.0
 * @link     https://pay.sh6.me/
 */

! defined('ABSPATH') && exit;
define('INC', ABSPATH . 'includes/');

// Load Functions.
$functions = scandir(INC . 'functions');

// Loop through the functions directory and include all files.
foreach ($functions as $function) {
    if (strpos($function, '.php') !== false) {
        include_once INC . 'functions/' . $function;
    }
}
