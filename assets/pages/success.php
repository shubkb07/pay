<?php
/**
 * Success Page - Shown after successful payment
 */
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Pay</title>
    <meta name="description" content="Your payment has been successfully processed">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/tailwind.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/fontawesome.css'; ?>">
    <style>
        /* Animation for success icon */
        @keyframes checkmark {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-checkmark {
            animation: checkmark 0.8s ease-in-out forwards;
        }
        
        /* Accessibility styles */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
        
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
    <!-- Removed window.paymentData script block -->
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300 flex flex-col min-h-screen">
    <!-- Skip link for keyboard navigation -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
    <?php include_once ASSETS . 'components/header.php'; ?>
    
    <main id="main-content" class="flex-grow flex items-center justify-center py-12" tabindex="-1">
        <div class="container mx-auto px-8 md:px-12">
            <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center" role="region" aria-labelledby="success-title">
                <div class="mb-6" role="img" aria-label="Success checkmark">
                    <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-check text-green-500 dark:text-green-400 text-4xl animate-checkmark" aria-hidden="true"></i>
                    </div>
                </div>
                
                <h1 id="success-title" class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Payment Successful!</h1>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Thank you for your payment. Your transaction has been completed successfully.
                </p>
                
                <div id="pay-transaction-details" class="max-w-sm mx-auto bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6 text-left" aria-live="polite" aria-atomic="true">
                    <!-- Transaction details will be populated by JavaScript from CDATA -->
                    <p class="text-center text-gray-500 dark:text-gray-400">Loading transaction details...</p>
                </div>
                
                <div id="pay-redirect-section" class="hidden" aria-live="polite">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        You will be redirected in <span id="pay-countdown" class="font-bold text-blue-600 dark:text-blue-400" aria-label="seconds remaining">30</span> seconds. 
                        Or click the button below to go now:
                    </p>
                    
                    <div>
                        <a 
                            id="pay-redirect-button" 
                            href="#" 
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                            Continue to Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script type="text/javascript">
    //<![CDATA[
    var data = {
        "status": "success",
        "transaction": {
            "Transaction ID": "PAY1234567890",
            "Date": "Jan 01, 2022 12:00",
            "Amount": "₹ 100.00",
            "Status": "Success",
            "Payment Method": "Credit Card"
        }
    };
    //]]>
    </script>
    <?php include_once ASSETS . 'components/status_footer.php'; ?>
    
    <script type="module" src="<?php echo ASSETS_URL . 'js/status.js'; ?>"></script>
</body>
</html>
