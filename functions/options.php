<?php

/**
 * Options Functions.
 */

/**
 * Fetch Options.
 */
function fetch_options() {
    global $db;
    
    // Get options with autoload='yes'
    $options = $db->select_data(
        'options',
        ['option_name', 'option_value'],
        ['autoload' => 'yes']
    );
    
    $result = [];
    
    // Format options as key-value pairs
    if (!empty($options) && is_array($options)) {
        foreach ($options as $option) {
            $result[$option['option_name']] = $option['option_value'];
        }
    }
    
    return $result;
}