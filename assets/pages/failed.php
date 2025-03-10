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
    </style>
    <!-- Removed window.paymentData script block -->
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300 flex flex-col min-h-screen">
    <?php include_once ASSETS . 'components/header.php'; ?>
    
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="container mx-auto px-8 md:px-12">
            <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-times text-red-500 dark:text-red-400 text-4xl animate-error"></i>
                    </div>
                </div>
                
                <h1 class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Payment Failed</h1>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Sorry, your payment could not be processed. Please try again or contact support.
                </p>
                
                <div id="pay-error-details" class="max-w-sm mx-auto bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-lg p-4 mb-6 text-left">
                    <!-- Error details will be populated by JavaScript from CDATA -->
                    <p class="text-center text-gray-500 dark:text-gray-400">Loading error details...</p>
                </div>
                
                <div id="pay-redirect-section" class="hidden"> <!-- Will be shown by JavaScript if redirect is available -->
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        You will be redirected in <span id="pay-countdown" class="font-bold text-blue-600 dark:text-blue-400">30</span> seconds. 
                        Or click the button below to go now:
                    </p>
                    
                    <div>
                        <a id="pay-redirect-button" href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded transition duration-300">
                            Return to Website
                        </a>
                    </div>
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
