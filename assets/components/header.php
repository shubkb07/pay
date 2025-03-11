<header class="sticky top-0 w-full bg-white dark:bg-gray-900 shadow-md transition duration-300 z-50" role="banner">
    <div class="container mx-auto px-8 md:px-12 py-4"> <!-- Increased horizontal padding -->
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center" aria-label="Pay homepage">
                    <span id="pay-logo" class="text-2xl font-bold text-blue-600 dark:text-blue-400">Pay</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8" aria-label="Main navigation">
                <a href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Home</a>
                <a href="/features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Features</a>
                <a href="/pricing" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Pricing</a>
                <a href="/about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">About</a>
                <a href="/contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Contact</a>
            </nav>

            <!-- Mobile Menu Button -->
            <button 
                id="pay-mobile-menu-button" 
                class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                aria-expanded="false"
                aria-controls="pay-mobile-menu"
                aria-label="Toggle menu">
                <i class="fas fa-bars" aria-hidden="true"></i>
                <span class="sr-only">Menu</span>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div 
            id="pay-mobile-menu" 
            class="hidden absolute top-full left-0 right-0 bg-white dark:bg-gray-900 shadow-lg border-t border-gray-200 dark:border-gray-700 z-20"
            role="navigation"
            aria-label="Mobile navigation">
            <div class="container mx-auto px-4 py-4">
                <div class="flex flex-col space-y-3" role="menu">
                    <a href="/" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition" role="menuitem">Home</a>
                    <a href="/features" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition" role="menuitem">Features</a>
                    <a href="/pricing" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition" role="menuitem">Pricing</a>
                    <a href="/about" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition" role="menuitem">About</a>
                    <a href="/contact" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition" role="menuitem">Contact</a>
                    
                    <!-- Close button for keyboard accessibility -->
                    <button 
                        id="pay-close-mobile-menu" 
                        class="mt-4 py-2 border border-gray-300 dark:border-gray-600 rounded text-gray-700 dark:text-gray-300 hover:border-blue-500 dark:hover:border-blue-400 transition"
                        aria-label="Close menu">
                        <i class="fas fa-times mr-2" aria-hidden="true"></i> Close Menu
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Navigation Overlay -->
<div 
    id="pay-mobile-menu-overlay" 
    class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-10"
    tabindex="-1"
    aria-hidden="true">
</div>

<!-- Remove body padding and add scroll margin for section targets -->
<style>
    html {
        scroll-padding-top: 5rem;
    }
    
    /* Ensure backdrop blur works across browsers */
    @supports (backdrop-filter: blur(4px)) {
        #pay-mobile-menu-overlay {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.2);
        }
    }
    
    /* Accessibility focus styles */
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

<!-- Skip link for accessibility -->
<a href="#main-content" class="skip-to-content">Skip to main content</a>
