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
            transactionDetails: document.getElementById('pay-transaction-details'),
            errorDetails: document.getElementById('pay-error-details')
        },
        
        // Get all data from CDATA
        data: window.data || {},
        
        // Countdown timer
        timer: null,
        secondsLeft: 30,
        
        init() {
            // Set page title based on status
            if (this.data.status) {
                document.title = this.data.status === 'success' 
                    ? 'Payment Successful - Pay' 
                    : 'Payment Failed - Pay';
            }
            
            // Process transaction data
            this.processTransactionData();
            
            // Check if we have redirect data
            if (this.data.redirect_to) {
                this.initRedirect(this.data.redirect_to);
            }
        },
        
        processTransactionData() {
            // Check if we have transaction data
            if (!this.data.transaction) return;
            
            const transaction = this.data.transaction;
            
            if (this.data.status === 'success' && this.elements.transactionDetails) {
                // Clear existing content
                this.elements.transactionDetails.innerHTML = '';
                
                // Add each transaction field dynamically
                Object.entries(transaction).forEach(([key, value]) => {
                    const row = document.createElement('p');
                    row.className = 'flex justify-between mb-2';
                    row.innerHTML = `
                        <span class="text-gray-600 dark:text-gray-300">${key}:</span>
                        <span class="font-medium text-gray-900 dark:text-blue-300">${value}</span>
                    `;
                    this.elements.transactionDetails.appendChild(row);
                });
            } 
            else if (this.data.status === 'failed' && this.elements.errorDetails) {
                // Clear existing content
                this.elements.errorDetails.innerHTML = '';
                
                // Add error message if available
                if (transaction['Error Message']) {
                    const errorMsg = document.createElement('p');
                    errorMsg.className = 'text-red-700 dark:text-red-300 mb-3';
                    errorMsg.innerHTML = `<span class="font-medium">Error:</span> ${transaction['Error Message']}`;
                    this.elements.errorDetails.appendChild(errorMsg);
                }
                
                // Add each transaction field dynamically
                Object.entries(transaction).forEach(([key, value]) => {
                    // Skip adding Error Message again since we already added it
                    if (key === 'Error Message') return;
                    
                    const row = document.createElement('p');
                    row.className = 'flex justify-between text-sm mb-2';
                    row.innerHTML = `
                        <span class="text-gray-600 dark:text-gray-300">${key}:</span>
                        <span class="text-gray-700 dark:text-gray-200">${value}</span>
                    `;
                    this.elements.errorDetails.appendChild(row);
                });
            }
        },
        
        initRedirect(url) {
            if (!url) return;
            
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
        }
    };
    
    // Initialize the page
    statusPage.init();
});
