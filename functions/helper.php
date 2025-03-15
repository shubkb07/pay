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

/**
 * Generate CDATA for JavaScript
 *
 * @param string $name Variable name
 * @param mixed $data Data to set on variable
 * @return string JavaScript code with CDATA
 */
function generate_cdata($name, $data) {
    $json_data = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    return <<<HTML
    <script type="text/javascript">
    //<![CDATA[
    var {$name} = {$json_data};
    //]]>
    </script>
    HTML;
}
