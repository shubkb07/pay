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
    </style>
    <script>
        // Add payment data for the status.js script
        window.paymentData = {
            // This will be replaced by PHP with actual data
            <?php if(isset($redirectUrl)): ?>
            redirect_to: "<?php echo htmlspecialchars($redirectUrl); ?>",
            <?php endif; ?>
            status: "success",
            transaction_id: "<?php echo isset($transactionId) ? htmlspecialchars($transactionId) : ''; ?>"
        };
    </script>
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300 flex flex-col min-h-screen">
    <?php include_once ASSETS . 'components/header.php'; ?>
    
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="container mx-auto px-8 md:px-12">
            <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-check text-green-500 dark:text-green-400 text-4xl animate-checkmark"></i>
                    </div>
                </div>
                
                <h1 class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Payment Successful!</h1>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Thank you for your payment. Your transaction has been completed successfully.
                </p>
                
                <div id="pay-transaction-details" class="max-w-sm mx-auto bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6 text-left">
                    <p class="flex justify-between mb-2">
                        <span class="text-gray-600 dark:text-gray-300">Transaction ID:</span>
                        <span id="pay-transaction-id" class="font-medium text-gray-900 dark:text-gray-100">
                            <?php echo isset($transactionId) ? htmlspecialchars($transactionId) : 'N/A'; ?>
                        </span>
                    </p>
                    <p class="flex justify-between mb-2">
                        <span class="text-gray-600 dark:text-gray-300">Date:</span>
                        <span id="pay-transaction-date" class="font-medium text-gray-900 dark:text-gray-100">
                            <?php echo date('M d, Y H:i'); ?>
                        </span>
                    </p>
                </div>
                
                <div id="pay-redirect-section" class="hidden"> <!-- Will be shown by JavaScript if redirect is available -->
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        You will be redirected in <span id="pay-countdown" class="font-bold text-blue-600 dark:text-blue-400">30</span> seconds. 
                        Or click the button below to go now:
                    </p>
                    
                    <div>
                        <a id="pay-redirect-button" href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded transition duration-300">
                            Continue to Website
                        </a>
                    </div>
                </div>
                
                <!-- Removed "Back to Home" link -->
            </div>
        </div>
    </main>
    
    <?php include_once ASSETS . 'components/status_footer.php'; ?>
    
    <script type="module" src="<?php echo ASSETS_URL . 'js/status.js'; ?>"></script>
</body>
</html>
