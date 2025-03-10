<?php

/**
 * Home Page.
 */

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay - Simplified Payment Solutions</title>
    <meta name="description" content="Pay is a personal project by Shubham Kumar Bansal for simplified payment solutions with PayU integration.">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/tailwind.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/fontawesome.css'; ?>">
    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300">
    <?php include_once ASSETS . 'components/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 id="pay-hero-title" class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 opacity-0">
                    Simplified Payment Solutions
                </h1>
                <p id="pay-hero-subtitle" class="text-xl md:text-2xl mb-8 text-blue-100 opacity-0">
                    Easy payment tracking, secure payment links, and seamless integration with PayU
                </p>
                <div id="pay-hero-buttons" class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 opacity-0">
                    <a href="/register" class="bg-white text-blue-700 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold shadow-lg transition">
                        Get Started
                    </a>
                    <a href="/features" class="border border-white text-white hover:bg-white hover:text-blue-700 px-8 py-3 rounded-lg font-semibold transition">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
        
        <div class="container mx-auto px-4 mt-12">
            <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <img src="https://via.placeholder.com/1200x600/f3f4f6/a3a3a3?text=Payment+Dashboard" alt="Payment Dashboard Preview" class="w-full h-auto">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Powerful Features</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Everything you need to manage payments efficiently
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg hover:shadow-xl transition animate-on-scroll">
                    <div class="text-blue-600 dark:text-blue-400 mb-4">
                        <i class="fas fa-link fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Payment Links</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Create customized payment links to share with your customers via email, SMS, or social media.
                    </p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg hover:shadow-xl transition animate-on-scroll">
                    <div class="text-blue-600 dark:text-blue-400 mb-4">
                        <i class="fas fa-credit-card fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Payment Records</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Keep track of all your transactions in one place with detailed payment records and analytics.
                    </p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg hover:shadow-xl transition animate-on-scroll">
                    <div class="text-blue-600 dark:text-blue-400 mb-4">
                        <i class="fas fa-tags fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Promo Codes</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Create and manage discount codes, offers, and coupons to boost your sales and customer loyalty.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Personal Project Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto animate-on-scroll">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/3 mb-8 md:mb-0">
                        <div class="w-48 h-48 mx-auto rounded-full overflow-hidden">
                            <img src="https://via.placeholder.com/300x300/e2e8f0/64748b?text=SKB" alt="Shubham Kumar Bansal" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="md:w-2/3 md:pl-12">
                        <h2 class="text-3xl font-bold mb-4">Personal Project</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                            Pay is a personal project developed by Shubham Kumar Bansal to simplify payment management. It's designed to be a comprehensive solution for handling payment records, generating payment links, and integrating with PayU as a payment gateway.
                        </p>
                        
                        <div class="flex space-x-4">
                            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                <i class="fab fa-linkedin fa-2x"></i>
                            </a>
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                <i class="fab fa-facebook fa-2x"></i>
                            </a>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted Websites Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-3xl font-bold mb-4">Trusted Resources</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Explore other projects and websites by Shubham Kumar Bansal
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 max-w-4xl mx-auto">
                <a href="https://shubkb.com" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-md text-center transition animate-on-scroll">
                    <div class="text-gray-700 dark:text-gray-300 font-medium">shubkb.com</div>
                </a>
                <a href="https://shubkb.me" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-md text-center transition animate-on-scroll">
                    <div class="text-gray-700 dark:text-gray-300 font-medium">shubkb.me</div>
                </a>
                <a href="https://shubkb.in" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-md text-center transition animate-on-scroll">
                    <div class="text-gray-700 dark:text-gray-300 font-medium">shubkb.in</div>
                </a>
                <a href="https://sh6.me" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-md text-center transition animate-on-scroll">
                    <div class="text-gray-700 dark:text-gray-300 font-medium">sh6.me</div>
                </a>
                <a href="https://sync.org.in" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-md text-center transition animate-on-scroll">
                    <div class="text-gray-700 dark:text-gray-300 font-medium">sync.org.in</div>
                </a>
                <a href="https://chrome.google.com/webstore" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-md text-center transition animate-on-scroll">
                    <div class="text-gray-700 dark:text-gray-300 font-medium">WEhizzy</div>
                </a>
            </div>
        </div>
    </section>

    <!-- Payment Integration Section -->
    <section class="py-16 bg-blue-50 dark:bg-gray-900">
    <?php include_once ASSETS . 'components/footer.php'; ?>
    <script type="module" src="<?php echo ASSETS_URL . 'js/home.js'; ?>"></script>
</body>
</html>