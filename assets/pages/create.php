<?php

/**
 * Create Payment Page.
 * 
 * Handles a multi-step payment flow:
 * 1. Email collection
 * 2. Optional coupon application
 * 3. Billing details
 */

use Pay\User;

// Initialize global variables.
global $user_exists, $is_blocked;
$page = '';

// Decode payment ID from JWT token.
$options = jwt_decode_token($pay_id);

echo json_encode($options);

// Validate product_id exists and is valid.
if (!isset($options['product_id']) || !is_numeric($options['product_id'])) {
    include_once ASSETS . 'pages/404.php';
    exit;
}

// Verify product exists in database.
$product = $pay->get_product($options['product_id']);
if (empty($product) || !isset($product['id']) || !is_array($product)) {
    include_once ASSETS . 'pages/404.php';
    exit;
}

/**
 * Verify if email exists in the database and check account status.
 *
 * @param string $email Email to verify.
 *
 * @return void Updates global variables $user_exists and $is_blocked.
 */
function verify_email($email) {
    global $user_exists, $is_blocked;
    $user = new User($email);
    $user_exists = $user->exists;
    $is_blocked = $user_exists && $user->is_blocked;
}

/**
 * Redirect to the current page with updated options.
 *
 * @param array $options Options to encode in JWT token.
 *
 * @return void Exits after redirect
 */
function redirect_with_options($options) {
    header('Location: /pay/c/' . jwt_create_token($options));
    exit;
}

// Handle email step
if (isset($_POST['email'])) {
    // Process submitted email.
    $options['email'] = $_POST['email'];
    verify_email($_POST['email']);
    redirect_with_options($options);
} elseif (isset($options['email'])) {
    // Email already in options, verify it.
    verify_email($options['email']);
} else {
    // No email provided yet, show email page.
    $page = 'email';
}

// Handle coupon step.
if ($page !== 'email') {
    if (isset($_POST['coupon'])) {
        // Process submitted coupon.
        $options['coupon'] = $_POST['coupon'];
        redirect_with_options($options);
    } elseif (isset($options['coupon'])) {
        // Coupon already in options, validate it.
        if (!empty($options['email']) && !empty($options['coupon'])) {
            // Validate coupon against product and email.
            $coupon = $pay->can_coupon_be_applied($options['email'], $options['coupon'], $options['product_id']);
            if (!$coupon) {
                // Invalid coupon, remove it.
                unset($options['coupon']);
                redirect_with_options($options);
            }
        } elseif (empty($options['email'])) {
            // Email missing, remove coupon and redirect.
            unset($options['coupon']);
            redirect_with_options($options);
        }
    } elseif ($product['can_percentage_coupon_apply'] === '1' ||
              $product['can_price_coupon_apply'] === '1' ||
              $product['can_free_trial_coupon_apply'] === '1') {
        // Product allows coupons, show coupon page.
        $page = 'coupon';
    } else {
        // Product doesn't allow coupons, skip to billing.
        $options['coupon'] = '';
        redirect_with_options($options);
    }
}

// Determine current page based on available data.
if ($page === '') {
    if (isset($options['email']) && isset($options['coupon'])) {
        $page = 'billing';
    } elseif (isset($options['email'])) {
        $page = 'coupon';
    } else {
        $page = 'email';
    }
}

// Set the page title based on current step
$page_titles = [
    'email'   => 'Enter Email - Pay',
    'coupon'  => 'Apply Coupon - Pay',
    'billing' => 'Billing Details - Pay',
    'blocked' => 'Email Blocked - Pay',
];

// Create page data for JavaScript
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