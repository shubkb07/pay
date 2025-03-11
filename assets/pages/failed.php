<?php

/**
 * Failed Page - Shown after failed payment
 */

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Pay</title>
    <meta name="description" content="Your payment could not be processed">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/tailwind.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/fontawesome.css'; ?>">
    <style>
        /* Animation for error icon */
        @keyframes errorPulse {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-error {
            animation: errorPulse 0.8s ease-in-out forwards;
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
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300 flex flex-col min-h-screen">
    <!-- Skip link for keyboard navigation -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
    <?php include_once ASSETS . 'components/header.php'; ?>
    
    <main id="main-content" class="flex-grow flex items-center justify-center py-12" tabindex="-1">
        <div class="container mx-auto px-8 md:px-12">
            <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center" role="region" aria-labelledby="error-title">
                <div class="mb-6" role="img" aria-label="Error indicator">
                    <div class="w-20 h-20 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-times text-red-500 dark:text-red-400 text-4xl animate-error" aria-hidden="true"></i>
                    </div>
                </div>
                
                <h1 id="error-title" class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Payment Failed</h1>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Sorry, your payment could not be processed. Please try again or contact support.
                </p>
                
                <div id="pay-error-details" class="max-w-sm mx-auto bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-lg p-4 mb-6 text-left" aria-live="polite" aria-atomic="true" role="alert">
                    <!-- Error details will be populated by JavaScript from CDATA -->
                    <p class="text-center text-gray-500 dark:text-gray-400">Loading error details...</p>
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
                            Return to Website
                        </a>
                    </div>
                </div>
                
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h2 class="text-lg font-semibold mb-3">Need Help?</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        If you continue experiencing issues, please contact our support team.
                    </p>
                    <a 
                        href="/contact" 
                        class="inline-block bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 py-2 px-6 rounded transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <script type="text/javascript">
    //<![CDATA[
    var data = {
        "status": "failed",
        "redirect_to": "https://example.com/payment-failed", // This will be replaced with actual redirect URL
        "transaction": {
            "Transaction ID": "PAY1234567890",
            "Date": "Jan 01, 2022 12:00",
            "Amount": "₹ 100.00",
            "Status": "Failed",
            "Error Code": "ERR-1001",
            "Error Message": "Your payment was declined by the bank. Please try another card."
        }
    };
    //]]>
    </script>
    <?php include_once ASSETS . 'components/status_footer.php'; ?>
    
    <script type="module" src="<?php echo ASSETS_URL . 'js/status.js'; ?>"></script>
</body>
</html>
