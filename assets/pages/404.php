<?php

/**
 * 404 Error Page - Shown when a page is not found
 */

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Pay</title>
    <meta name="description" content="The requested page could not be found">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/tailwind.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/fontawesome.css'; ?>">
    <style>
        /* Animation for 404 icon */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Animation for fade-in elements */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300 flex flex-col min-h-screen">
    <?php include_once ASSETS . 'components/header.php'; ?>
    
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="container mx-auto px-8 md:px-12">
            <div class="max-w-2xl mx-auto text-center">
                <div class="mb-8">
                    <div class="w-32 h-32 mx-auto relative animate-float">
                        <i class="fas fa-map-signs text-blue-600 dark:text-blue-400 text-7xl"></i>
                        <div class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            404
                        </div>
                    </div>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900 dark:text-gray-100 animate-fade-in">
                    Page Not Found
                </h1>
                
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 animate-fade-in delay-100">
                    We couldn't find the page you were looking for. It might have been moved, deleted, or never existed.
                </p>
                
                <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8 animate-fade-in delay-200">
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        <i class="fas fa-search mr-2 text-blue-500"></i>
                        You might want to double-check the URL or go back to a known page.
                    </p>
                    
                    <p id="pay-requested-url" class="text-sm font-mono bg-gray-100 dark:bg-gray-700 p-2 rounded overflow-x-auto">
                        <!-- Will be populated by JavaScript -->
                        Loading requested URL...
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 animate-fade-in delay-300">
                    <button id="pay-go-back" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        <i class="fas fa-arrow-left mr-2"></i> Go Back
                    </button>
                    
                    <a href="/" class="border border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 py-2 px-6 rounded-lg transition duration-300">
                        <i class="fas fa-home mr-2"></i> Go Home
                    </a>
                </div>
            </div>
        </div>
    </main>
    
    <?php include_once ASSETS . 'components/footer.php'; ?>
    
    <script type="module" src="<?php echo ASSETS_URL . 'js/404.js'; ?>"></script>
</body>
</html>
