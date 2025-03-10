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
define('INC', ABSPATH . 'inc/');

// Load Functions.
$functions = scandir(INC . 'functions');
var_dump($functions);
