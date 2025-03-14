<?php

/**
 * Helper Functions.
 */

/**
 * Get UA Id.
 *
 * @param string $ua User Agent.
 *
 * @return integer
 */
function get_ua_id($ua) {
    global $db;

    // Try to find the user agent in the database.
    $result = $db->select_data('ua', ['id'], ['ua' => $ua]);

    // If the user agent already exists, return its ID.
    if (!empty($result)) {
        return $result[0]['id'];
    }

    // If not found, insert the user agent and return the new ID.
    $insert_result = $db->insert_data('ua', ['ua' => $ua]);
    return $insert_result['insert_id'];
}

/**
 * QR Code Generator.
 *
 * Returns PNG image data.
 *
 * @return string PNG image data.
 */
function qr_code($data) {
    // Include the library.
    require_once INC . 'lib/phpqrcode/qrlib.php';

    // Generate the QR code.
    ob_start();
    QRcode::png($data, null, QR_ECLEVEL_L, 10, 2);
    $image_data = ob_get_contents();
    ob_end_clean();

    return $image_data;
}
