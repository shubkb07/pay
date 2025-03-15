<?php

/**
 * Options Functions.
 */

// Initialize the global options array.
global $option;
$option = [];

/**
 * Fetch Options.
 *
 * Fetch options from database and store in global variable.
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

// Call the function to load options at initialization.
fetch_options();

/**
 * Get Option.
 *
 * @param string $option_name Option Name.
 *
 * @return string|null Option Value.
 */
function get_option($option_name) {
    global $db, $option;

    // If present in global variable, return it, else fetch from database.
    if (isset($option[$option_name])) {
        return $option[$option_name];
    } else {
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

/**
 * Update Option.
 *
 * @param string $option_name  Option Name.
 * @param string $option_value Option Value.
 * @param string $autoload     Autoload.
 *
 * @return boolean True if updated, false otherwise.
 */
function update_option($option_name, $option_value, $autoload = 'yes') {
    global $db, $option;

    // Check in global variable, if exist and same, return true.
    // Else If exist find using get_option, then update and return true.
    // Else insert new option and return true.
    if (isset($option[$option_name]) && $option[$option_name] === $option_value) {
        return true;
    } elseif (get_option($option_name) !== null) {
        $result = $db->update_data(
            'options',
            ['option_value' => $option_value],
            ['option_name' => $option_name]
        );

        if ($result) {
            $option[$option_name] = $option_value;
            return true;
        }
    } else {
        $result = $db->insert_data(
            'options',
            [
             'option_name'  => $option_name,
             'option_value' => $option_value,
             'autoload'     => $autoload,
            ]
        );

        if ($result) {
            $option[$option_name] = $option_value;
            return true;
        }
    }
}

/**
 * Delete Option.
 *
 * @param string $option_name Option Name.
 *
 * @return boolean True if deleted, false otherwise.
 */
function delete_option($option_name) {
    global $db, $option;

    // Check if exist using get_option, then delete in db and global and return true.
    if (get_option($option_name) !== null) {
        $result = $db->delete_data(
            'options',
            ['option_name' => $option_name]
        );

        if ($result) {
            unset($option[$option_name]);
            return true;
        }
    }

    return false;
}
