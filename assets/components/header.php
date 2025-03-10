<header class="sticky top-0 bg-white dark:bg-gray-900 shadow-md transition duration-300 z-50">
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center">
                    <span id="pay-logo" class="text-2xl font-bold text-blue-600 dark:text-blue-400">Pay</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Home</a>
                <a href="/features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Features</a>
                <a href="/pricing" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Pricing</a>
                <a href="/about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">About</a>
                <a href="/contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Contact</a>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="pay-mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Navigation Overlay -->
    <div id="pay-mobile-menu-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
    
    <!-- Mobile Navigation -->
    <div id="pay-mobile-menu" class="md:hidden hidden fixed top-16 right-0 left-0 bg-white dark:bg-gray-900 shadow-lg z-40 border-t border-gray-200 dark:border-gray-700 max-h-screen overflow-y-auto">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col space-y-3">
                <a href="/" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Home</a>
                <a href="/features" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Features</a>
                <a href="/pricing" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Pricing</a>
                <a href="/about" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">About</a>
                <a href="/contact" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Contact</a>
            </div>
        </div>
    </div>
</header>
