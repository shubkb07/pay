/**
 * Status page functionality for success and failure pages
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize the status page functionality
    const statusPage = {
        // DOM elements
        elements: {
            redirectSection: document.getElementById('pay-redirect-section'),
            redirectButton: document.getElementById('pay-redirect-button'),
            countdown: document.getElementById('pay-countdown'),
        },
        
        // Payment data from the page
        paymentData: window.paymentData || {},
        
        // Countdown timer
        timer: null,
        secondsLeft: 30,
        
        init() {
            // Check if we have redirect data
            if (this.paymentData && this.paymentData.redirect_to) {
                this.initRedirect(this.paymentData.redirect_to);
            }
            
            // Initialize animations based on status
            this.initStatusSpecificFeatures();
        },
        
        initRedirect(url) {
            // Show the redirect section
            if (this.elements.redirectSection) {
                this.elements.redirectSection.classList.remove('hidden');
            }
            
            // Set up the redirect button
            if (this.elements.redirectButton) {
                this.elements.redirectButton.href = url;
            }
            
            // Start the countdown
            this.startCountdown(url);
        },
        
        startCountdown(url) {
            // Clear any existing timers
            if (this.timer) clearInterval(this.timer);
            
            // Update countdown every second
            this.timer = setInterval(() => {
                // Decrement counter
                this.secondsLeft--;
                
                // Update displayed countdown
                if (this.elements.countdown) {
                    this.elements.countdown.textContent = this.secondsLeft;
                }
                
                // Redirect when countdown reaches zero
                if (this.secondsLeft <= 0) {
                    clearInterval(this.timer);
                    window.location.href = url;
                }
            }, 1000);
        },
        
        initStatusSpecificFeatures() {
            // Apply specific behaviors based on payment status
            if (this.paymentData.status === 'success') {
                // Success-specific actions if needed
                document.title = 'Payment Successful - Pay';
                
                // Add transaction ID if available but not yet in the DOM
                if (this.paymentData.transaction_id && document.getElementById('pay-transaction-id')) {
                    document.getElementById('pay-transaction-id').textContent = this.paymentData.transaction_id;
                }
            } 
            else if (this.paymentData.status === 'failed') {
                document.title = 'Payment Failed - Pay';
                
                // Add error details if available
                const errorDetails = document.getElementById('pay-error-details');
                if (errorDetails && this.paymentData.error_message && !errorDetails.textContent.includes(this.paymentData.error_message)) {
                    const errorElem = document.createElement('p');
                    errorElem.className = 'text-red-700 dark:text-red-400 mb-2';
                    errorElem.innerHTML = `<span class="font-medium">Error:</span> ${this.paymentData.error_message}`;
                    errorDetails.appendChild(errorElem);
                }
            }
        }
    };
    
    // Initialize the page
    statusPage.init();
});
