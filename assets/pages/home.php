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

    <!-- Improved SEO title with brand and keyword -->
    <title>Pay - Simplified Payment Solutions for Website Integration | PayU Partner</title>

    <!-- Enhanced meta description with clear value proposition and keywords -->
    <meta name="description" content="Pay offers secure payment integration solutions with PayU, enabling easy payment tracking, secure payment links, and seamless checkout experiences for your website or application.">

    <!-- Additional SEO meta tags -->
    <meta name="keywords" content="payment gateway, PayU integration, payment links, payment processing, secure payments, online payments, payment tracking">
    <meta name="author" content="Shubham Kumar Bansal">

    <!-- Canonical URL to prevent duplicate content issues -->
    <link rel="canonical" href="https://pay.example.com">

    <!-- Open Graph meta tags for better social sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Pay - Simplified Payment Solutions">
    <meta property="og:description" content="Secure payment gateway integration with PayU for websites and apps. Easy setup, detailed records, and seamless checkout experiences.">
    <meta property="og:url" content="https://pay.example.com">
    <meta property="og:image" content="<?php echo ASSETS_URL . 'images/pay-og-image.jpg'; ?>">
    <meta property="og:site_name" content="Pay">

    <!-- Twitter Card meta tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Pay - Simplified Payment Solutions">
    <meta name="twitter:description" content="Secure payment gateway integration with PayU for websites and apps. Easy setup, detailed records, and seamless checkout experiences.">
    <meta name="twitter:image" content="<?php echo ASSETS_URL . 'images/pay-twitter-card.jpg'; ?>">

    <!-- Favicon links -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo ASSETS_URL . 'images/favicon/apple-touch-icon.png'; ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo ASSETS_URL . 'images/favicon/favicon-32x32.png'; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo ASSETS_URL . 'images/favicon/favicon-16x16.png'; ?>">
    <link rel="manifest" href="<?php echo ASSETS_URL . 'images/favicon/site.webmanifest'; ?>">

    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/tailwind.css'; ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_URL . 'css/fontawesome.css'; ?>">

    <!-- Preload critical resources -->
    <link rel="preload" href="<?php echo ASSETS_URL . 'js/home.js'; ?>" as="script">
    <link rel="preload" href="<?php echo ASSETS_URL . 'js/app.js'; ?>" as="script">

    <style>
        /* Simpler, more reliable animation system */
        .appear-once {
            opacity: 0;
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .animate-fade-in {
            opacity: 1 !important;
            /* Use !important to ensure it overrides */
            transform: translateY(0) !important;
        }

        /* Default transform that will be reset when visible */
        .appear-once {
            transform: translateY(20px);
        }

        /* Hero section animations */
        #pay-hero-title,
        #pay-hero-subtitle,
        #pay-hero-buttons {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        #pay-hero-title.opacity-100,
        #pay-hero-subtitle.opacity-100,
        #pay-hero-buttons.opacity-100 {
            opacity: 1;
            transform: translateY(0);
        }

        /* Card hover effects */
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .feature-card:hover .feature-icon {
            color: #3b82f6;
            transform: scale(1.1) rotate(10deg);
        }

        /* For scroll offsets with sticky header */
        section[id] {
            scroll-margin-top: 5rem;
        }

        /* Add a debug helper class that can be added to troubleshoot */
        .debug-show {
            opacity: 1 !important;
            border: 2px solid red !important;
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
    </style>

    <!-- JSON-LD structured data for rich search results -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "Pay",
            "applicationCategory": "FinanceApplication",
            "operatingSystem": "Web",
            "description": "A payment system for websites with PayU integration. Offers payment tracking, secure payment links, and seamless checkout experiences.",
            "offers": {
                "@type": "Offer",
                "price": "0",
                "priceCurrency": "INR"
            },
            "author": {
                "@type": "Person",
                "name": "Shubham Kumar Bansal"
            },
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.8",
                "reviewCount": "127"
            }
        }
    </script>

    <!-- FAQ structured data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [{
                    "@type": "Question",
                    "name": "How does Pay work with my website?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Pay connects your website to PayU payment gateway, handling payment records, payment cards, promo codes, and discount management. Only payment invoices and necessary details are stored on our system."
                    }
                },
                {
                    "@type": "Question",
                    "name": "Is my payment data secure with Pay?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Yes, Pay only stores payment invoices and basic information required for record keeping. Sensitive payment data remains secure with PayU."
                    }
                },
                {
                    "@type": "Question",
                    "name": "What features does Pay offer?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Pay offers payment links creation, detailed payment records and analytics, promo codes management, and seamless PayU integration."
                    }
                }
            ]
        }
    </script>
</head>

<body class="bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition duration-300">
    <?php include_once ASSETS . 'components/header.php'; ?>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-24" aria-labelledby="hero-heading">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 id="hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    Simplified Payment Solutions
                </h1>
                <p id="pay-hero-subtitle" class="text-xl md:text-2xl mb-8 text-blue-100">
                    Easy payment tracking, secure payment links, and seamless integration with PayU
                </p>
                <div id="pay-hero-buttons" class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4" role="group" aria-label="Hero navigation">
                    <a href="#how-it-works" class="bg-white text-blue-700 hover:bg-blue-50 hover:scale-105 px-8 py-3 rounded-lg font-semibold shadow-lg transition duration-300 transform">
                        How It Works
                    </a>
                    <a href="/features" class="border border-white text-white hover:bg-white hover:text-blue-700 hover:scale-105 px-8 py-3 rounded-lg font-semibold transition duration-300 transform">
                        Learn More
                    </a>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 mt-12">
            <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden animate-scale-in delay-500 hover:shadow-2xl transition duration-300">
                <div class="flex flex-col md:flex-row items-center p-8">
                    <div class="md:w-1/2 mb-8 md:mb-0 flex justify-center">
                        <div class="text-center md:text-left">
                            <i class="fas fa-exchange-alt text-blue-500 dark:text-blue-400 text-9xl mb-6 transform hover:scale-110 transition duration-300" role="img" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="md:w-1/2 md:pl-8 text-center md:text-left">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">Seamless Payment Processing</h2>
                        <p class="text-gray-600 dark:text-gray-300">
                            Connect your websites and applications to a reliable payment system with minimal setup.
                            PayU handles the payment processing while Pay manages records and provides advanced features.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20" id="how-it-works" aria-labelledby="features-heading">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 appear-once">
                <h2 id="features-heading" class="text-3xl md:text-4xl font-bold mb-4">Powerful Features</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Everything you need to manage payments efficiently
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" role="list">
                <div class="feature-card bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg hover:shadow-xl hover:bg-blue-50 dark:hover:bg-gray-700 appear-once" tabindex="0" role="listitem">
                    <div class="feature-icon text-blue-600 dark:text-blue-400 mb-4 transition-all duration-300" aria-hidden="true">
                        <i class="fas fa-link fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Payment Links</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Create customized payment links to share with your customers via email, SMS, or social media.
                    </p>
                </div>

                <div class="feature-card bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg hover:shadow-xl hover:bg-blue-50 dark:hover:bg-gray-700 appear-once" tabindex="0" role="listitem">
                    <div class="feature-icon text-blue-600 dark:text-blue-400 mb-4 transition-all duration-300" aria-hidden="true">
                        <i class="fas fa-credit-card fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Payment Records</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Keep track of all your transactions in one place with detailed payment records and analytics.
                    </p>
                </div>

                <div class="feature-card bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg hover:shadow-xl hover:bg-blue-50 dark:hover:bg-gray-700 appear-once" tabindex="0" role="listitem">
                    <div class="feature-icon text-blue-600 dark:text-blue-400 mb-4 transition-all duration-300" aria-hidden="true">
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

    <!-- Integration Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900" aria-labelledby="integration-heading">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto appear-once">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 mb-8 md:mb-0 flex justify-center" aria-hidden="true">
                        <i class="fas fa-shield-alt text-blue-500 dark:text-blue-400 text-9xl hover:scale-110 transition duration-300 transform"></i>
                    </div>
                    <div class="md:w-1/2 md:pl-12">
                        <h2 id="integration-heading" class="text-3xl font-bold mb-4">Secure Integration</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                            Pay connects your website to PayU payment gateway, handling payment records, payment cards, promo codes, and discount management. Only payment invoices and necessary details are stored on our system, while sensitive payment data remains secure with PayU.
                        </p>

                        <div class="bg-blue-50 dark:bg-gray-800 p-4 rounded-md border-l-4 border-blue-500 dark:border-blue-400 hover:shadow-md transition duration-300" role="note">
                            <p class="text-gray-700 dark:text-gray-300">
                                <i class="fas fa-info-circle mr-2 text-blue-500 dark:text-blue-400" aria-hidden="true"></i>
                                No payment method details are stored on this site other than payment invoices and basic information required for record keeping.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Websites Using Pay Section -->
    <section class="py-16" aria-labelledby="websites-heading" itemscope itemtype="https://schema.org/ItemList">
        <meta itemprop="itemListOrder" content="Unordered">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 appear-once">
                <h2 id="websites-heading" class="text-3xl font-bold mb-4" itemprop="name">Websites Using Pay</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    These websites and applications already use Pay for their payment processing
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 max-w-4xl mx-auto" role="list" aria-label="Websites using Pay">
                <a href="https://shubkb.com" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg hover:-translate-y-1 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-300 dark:hover:border-blue-500 border-2 border-transparent text-center transition duration-300 transform appear-once" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <meta itemprop="position" content="1">
                    <div class="text-gray-700 dark:text-gray-300 font-medium" itemprop="name">shubkb.com</div>
                </a>
                <a href="https://shubkb.me" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg hover:-translate-y-1 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-300 dark:hover:border-blue-500 border-2 border-transparent text-center transition duration-300 transform appear-once" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <meta itemprop="position" content="2">
                    <div class="text-gray-700 dark:text-gray-300 font-medium" itemprop="name">shubkb.me</div>
                </a>
                <a href="https://shubkb.in" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg hover:-translate-y-1 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-300 dark:hover:border-blue-500 border-2 border-transparent text-center transition duration-300 transform appear-once" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <meta itemprop="position" content="3">
                    <div class="text-gray-700 dark:text-gray-300 font-medium" itemprop="name">shubkb.in</div>
                </a>
                <a href="https://sh6.me" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg hover:-translate-y-1 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-300 dark:hover:border-blue-500 border-2 border-transparent text-center transition duration-300 transform appear-once" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <meta itemprop="position" content="4">
                    <div class="text-gray-700 dark:text-gray-300 font-medium" itemprop="name">sh6.me</div>
                </a>
                <a href="https://sync.org.in" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg hover:-translate-y-1 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-300 dark:hover:border-blue-500 border-2 border-transparent text-center transition duration-300 transform appear-once" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <meta itemprop="position" content="5">
                    <div class="text-gray-700 dark:text-gray-300 font-medium" itemprop="name">sync.org.in</div>
                </a>
                <a href="https://chrome.google.com/webstore" target="_blank" rel="noopener noreferrer" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg hover:-translate-y-1 hover:bg-blue-50 dark:hover:bg-gray-700 hover:border-blue-300 dark:hover:border-blue-500 border-2 border-transparent text-center transition duration-300 transform appear-once" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <meta itemprop="position" content="6">
                    <div class="text-gray-700 dark:text-gray-300 font-medium" itemprop="name">WEhizzy</div>
                </a>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="how-it-works-section" class="py-16 bg-blue-50 dark:bg-gray-900" aria-labelledby="how-it-works-heading">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 appear-once">
                <h2 id="how-it-works-heading" class="text-3xl md:text-4xl font-bold mb-4">How Pay Works</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    A simple process from payment creation to completion
                </p>
            </div>

            <!-- Process steps with better semantics and microdata -->
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8" role="list" aria-label="Process steps" itemscope itemtype="https://schema.org/ItemList">
                    <meta itemprop="numberOfItems" content="3">

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:shadow-xl hover:scale-105 hover:bg-gradient-to-b hover:from-white hover:to-blue-50 dark:hover:from-gray-800 dark:hover:to-gray-700 transition duration-300 transform appear-once card" tabindex="0" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <meta itemprop="position" content="1">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                            <span class="text-blue-600 dark:text-blue-400 text-2xl font-bold">1</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3" itemprop="name">Create Payment</h3>
                        <p class="text-gray-600 dark:text-gray-400" itemprop="description">
                            Generate a payment request with all required details and optional discounts
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:shadow-xl hover:scale-105 hover:bg-gradient-to-b hover:from-white hover:to-blue-50 dark:hover:from-gray-800 dark:hover:to-gray-700 transition duration-300 transform appear-once card" tabindex="0" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <meta itemprop="position" content="2">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                            <span class="text-blue-600 dark:text-blue-400 text-2xl font-bold">2</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3" itemprop="name">Secure Handoff</h3>
                        <p class="text-gray-600 dark:text-gray-400" itemprop="description">
                            Customers are securely redirected to PayU to complete the payment process
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center hover:shadow-xl hover:scale-105 hover:bg-gradient-to-b hover:from-white hover:to-blue-50 dark:hover:from-gray-800 dark:hover:to-gray-700 transition duration-300 transform appear-once card" tabindex="0" role="listitem" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <meta itemprop="position" content="3">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                            <span class="text-blue-600 dark:text-blue-400 text-2xl font-bold">3</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3" itemprop="name">Payment Complete</h3>
                        <p class="text-gray-600 dark:text-gray-400" itemprop="description">
                            After payment, customers return to your website with a verified transaction record
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once ASSETS . 'components/footer.php'; ?>
    <script type="module" src="<?php echo ASSETS_URL . 'js/home.js'; ?>"></script>
</body>

</html>