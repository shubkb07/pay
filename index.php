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

// Autoload Classes.
spl_autoload_register(
    function ($class) {
        // Load classes under namespace Pay.
        $prefix = 'Pay\\';

        // Does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // No, move to the next registered autoloader.
            return;
        }

        // Get the class name without the namespace.
        $class_name = substr($class, $len);

        // Replace namespace separators with directory separators.
        $class_path = str_replace('\\', '/', $class_name);

        // Get the last part of the class (actual class name).
        $class_parts = explode('/', $class_path);
        $class_file = end($class_parts);

        // Format the class filename.
        $file = INC . 'classes/class-' . strtolower($class_file) . '.php';

        // If the file exists, require it.
        if (file_exists($file)) {
            require_once $file;
        }
    }
);

// Load Functions.
$functions = array(
              'escape.php',
              'prepare.php',
             );

// Loop through the functions directory and include all files.
foreach ($functions as $function) {
    if (strpos($function, '.php') !== false) {
        include_once INC . 'functions/' . $function;
    }
}

