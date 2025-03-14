<footer class="bg-gray-100 dark:bg-gray-900 transition duration-300 mt-16" role="contentinfo">
    <div class="container mx-auto px-8 md:px-12 py-12"> <!-- Added matching padding to header -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8"> <!-- Changed to 3 columns -->
            <!-- About -->
            <div class="col-span-1">
                <h3 id="footer-about" class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Pay</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    A personal project by Shubham Kumar Bansal for handling payments, records, and creating payment links with PayU integration.
                </p>
                <div class="flex space-x-4 mt-4" aria-labelledby="footer-social-heading">
                    <span id="footer-social-heading" class="sr-only">Social Media Links</span>
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400" aria-label="LinkedIn">
                        <i class="fab fa-linkedin fa-lg" aria-hidden="true"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400" aria-label="Facebook">
                        <i class="fab fa-facebook fa-lg" aria-hidden="true"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400" aria-label="Instagram">
                        <i class="fab fa-instagram fa-lg" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            
            <!-- Links -->
            <div class="col-span-1">
                <h3 id="footer-quick-links" class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Quick Links</h3>
                <ul class="space-y-2" aria-labelledby="footer-quick-links">
                    <li><a href="/" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Home</a></li>
                    <li><a href="/features" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Features</a></li>
                    <li><a href="/pricing" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Pricing</a></li>
                    <li><a href="/about" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">About</a></li>
                    <li><a href="/contact" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Contact</a></li>
                </ul>
            </div>
            
            <!-- Legal -->
            <div class="col-span-1">
                <h3 id="footer-legal" class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Legal</h3>
                <ul class="space-y-2" aria-labelledby="footer-legal">
                    <li><a href="/terms" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Terms of Service</a></li>
                    <li><a href="/privacy" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Privacy Policy</a></li>
                    <li><a href="/refund" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Refund Policy</a></li>
                    <li><a href="/data-security" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Data Security</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 md:mb-0">
                © <?php echo date('Y'); ?> Pay. A Personal Project by Shubham Kumar Bansal. All rights reserved.
            </p>
            <p class="text-gray-500 dark:text-gray-500 text-sm">
                Powered by <a href="https://payu.in" target="_blank" rel="noopener noreferrer" class="hover:text-blue-600 dark:hover:text-blue-400" aria-label="Visit PayU website">PayU</a>
            </p>
        </div>
    </div>
</footer>
