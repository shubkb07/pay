<?php

/**
 * Create Payment Page.
 */

// Decode Pay ID.
$options = jwt_decode_token($pay_id);

// In options, product_id must be present and it must be a number, else 404.
if (!isset($options['product_id']) || !is_numeric($options['product_id'])) {
    include_once ASSETS . 'pages/404.php';
    exit;
}

if (isset($options['coupon']) && isset($options['email'])) {
    // If coupon and email key is set, then set $page = 'billing'.
    $page = 'billing';
} elseif (!isset($options['coupon']) && isset($options['email'])) {
    // If only email key is set, then set $page = 'coupon'.
    $page = 'coupon';
} else {
    // If none of the keys are set, then set $page = 'email'.
    $page = 'email';

    // If Get parameter 'coupon' is set, then set coupon in options.
    if (isset($_GET['coupon'])) {
        $options['coupon'] = $_GET['coupon'];
        // Make JWT token with options and redirect to the same page with coupon in URL.
        $token = jwt_create_token($options);
        header('Location: /pay/c/' . $token);
        exit;
    }
}

// If Get parameter 'email' is set, then set email in options.
if (isset($_GET['email'])) {
    $options['email'] = $_GET['email'];
    // Make JWT token with options and redirect to the same page with email in URL.
    $token = jwt_create_token($options);
    header('Location: /pay/c/' . $token);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300 flex flex-col min-h-screen">
    <!-- Skip link for keyboard navigation -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
    <?php include_once ASSETS . 'components/header.php'; ?>

    <main id="main-content" class="flex-grow flex items-center justify-center py-12" tabindex="-1">
    </main>

    <?php include_once ASSETS . 'components/status_footer.php'; ?>
    
    <script type="module" src="<?php echo ASSETS_URL . 'js/create.js'; ?>"></script>
</body>
</html>