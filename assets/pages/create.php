<?php

/**
 * Create Payment Page.
 */

// Decode Pay ID.
$options = jwt_decode_token($pay_id);
echo json_encode($options);
