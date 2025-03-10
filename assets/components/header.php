<header class="bg-white dark:bg-gray-900 shadow-md transition duration-300">
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

            <!-- Action Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                <button id="pay-theme-toggle" class="p-2 rounded-full text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i class="fas fa-moon dark:hidden"></i>
                    <i class="fas fa-sun hidden dark:block"></i>
                </button>
                <a href="/login" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Login</a>
                <a href="/register" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Register</a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="pay-mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="pay-mobile-menu" class="md:hidden hidden pt-4 pb-2">
            <div class="flex flex-col space-y-2">
                <a href="/" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Home</a>
                <a href="/features" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Features</a>
                <a href="/pricing" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Pricing</a>
                <a href="/about" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">About</a>
                <a href="/contact" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Contact</a>
                <div class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
                    <a href="/login" class="py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Login</a>
                    <a href="/register" class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Register</a>
                    <button id="pay-mobile-theme-toggle" class="p-2 rounded-full text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:block"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
