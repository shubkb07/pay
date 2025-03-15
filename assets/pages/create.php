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

    // If POST parameter 'coupon' is set, then set coupon in options.
    if (isset($_POST['coupon'])) {
        $options['coupon'] = $_POST['coupon'];
        // Make JWT token with options and redirect to the same page with coupon in URL.
        $token = jwt_create_token($options);
        header('Location: /pay/c/' . $token);
        exit;
    }
} else {
    // If none of the keys are set, then set $page = 'email'.
    $page = 'email';

    // If POST parameter 'email' is set, then set email in options.
    if (isset($_POST['email'])) {
        $options['email'] = $_POST['email'];
        // Make JWT token with options and redirect to the same page with email in URL.
        $token = jwt_create_token($options);
        header('Location: /pay/c/' . $token);
        exit;
    }
}

// Set the page title based on current step.
$page_titles = [
                'email'   => 'Enter Email - Pay',
                'coupon'  => 'Apply Coupon - Pay',
                'billing' => 'Billing Details - Pay',
                'blocked' => 'Email Blocked - Pay',
               ];

// Create page data for JavaScript.
$page_data = [
              'page'    => $page,
              'options' => $options,
             ];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_titles[$page] ?? 'Payment - Pay'; ?></title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/tailwind.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/fontawesome.css'; ?>">
    <style>
        /* Focus styles for keyboard navigation */
        :focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
        
        /* Skip link for keyboard users */
        .skip-to-content {
            position: absolute;
            top: -40px;
            left: 8px;
            background: #3b82f6;
            color: white;
            padding: 8px 16px;
            z-index: 100;
            transition: top 0.3s;
            border-radius: 0 0 4px 4px;
        }
        
        .skip-to-content:focus {
            top: 0;
        }
    </style>
    <?php echo generate_cdata('pageData', $page_data); ?>
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300 flex flex-col min-h-screen">
    <input id="pay-geo-json" value="<?php echo ASSETS_URL . 'json/geo.json' ?>" type="hidden">
    <!-- Skip link for keyboard navigation -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
    <?php include_once ASSETS . 'components/header.php'; ?>

    <main id="main-content" class="flex-grow flex items-center justify-center py-12" tabindex="-1">
        <div class="container mx-auto px-8 md:px-12">
            <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8" role="region">
                <?php
                // Check if the email is blocked - this logic would be implemented later.
                $email_blocked = false; // This will be replaced with actual logic.

                if ($page === 'email' && $email_blocked) {
                    include_once ASSETS . 'components/create_blocked_email.php';
                } elseif ($page === 'email') {
                    include_once ASSETS . 'components/create_email.php';
                } elseif ($page === 'coupon') {
                    include_once ASSETS . 'components/create_coupon.php';
                } elseif ($page === 'billing') {
                    include_once ASSETS . 'components/create_billing.php';
                }
                ?>
            </div>
        </div>
    </main>

    <?php include_once ASSETS . 'components/status_footer.php'; ?>
    
    <script type="module" src="<?php echo ASSETS_URL . 'js/create.js'; ?>"></script>
</body>
</html>