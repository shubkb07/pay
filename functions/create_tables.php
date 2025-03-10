<?php

/*
 * Create Tables.
 */

/**
 * Create Tables.
 */

function create_tables() {
    global $db;

    $create_table_array = json_decode(file_get_contents(INC . 'meta/json/create_tables.json'), true);

    if (empty($create_table_array) || !is_array($create_table_array)) {
        return false;
    }

    $result = [];

    foreach ($create_table_array as $table_name => $table_options) {
        try {
            $table_result = $db->create_new_table($table_name, $table_options);
            $result[$table_name] = $table_result;
        } catch (Exception $e) {
            $result[$table_name] = [
                                    'error'  => $e->getMessage(),
                                    'result' => false,
                                   ];
        }
    }
echo '<pre>';
print_r($result);
echo '</pre>';
    return $result;
}
