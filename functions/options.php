<?php

/**
 * Options Functions.
 */

// Initialize the global options array
global $option;
$option = [];

/**
 * Fetch Options.
 */
function fetch_options() {
    global $db, $option;

    // Get options with autoload='yes'.
    $options = $db->select_data(
        'options',
        array(
         'option_name',
         'option_value',
        ),
        ['autoload' => 'yes']
    );

    // Format options as key-value pairs.
    if (!empty($options) && is_array($options)) {
        foreach ($options as $single_option) {
            $option[$single_option['option_name']] = $single_option['option_value'];
        }
    }
}

// Call the function to load options at initialization/
fetch_options();

/**
 * Get Option.
 */
function get_option($option_name) {
    global $db, $option;

    // If present in global variable, return it, else fetch from database.
    if (isset($option[$option_name])) {
        echo 'From Global';
        return $option[$option_name];
    } else {
        echo 'From Database';
        $option_value = $db->select_data(
            'options',
            ['option_value'],
            ['option_name' => $option_name]
        );

        if (!empty($option_value) && is_array($option_value)) {
            return $option_value[0]['option_value'];
        }
    }

    return null;
}
