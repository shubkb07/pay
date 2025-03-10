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
        
        // Payment data from the page
        paymentData: window.paymentData || {},
        
        // Transaction data from CDATA
        transactionData: window.data || {},
        
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
            
            // Process transaction data from CDATA
            this.processTransactionData();
        },
        
        processTransactionData() {
            // Check if we have transaction data from CDATA
            if (this.transactionData && this.transactionData.transaction) {
                const transaction = this.transactionData.transaction;
                
                // For success page: Display transaction details
                if (this.elements.transactionDetails && this.paymentData.status === 'success') {
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
                
                // For failed page: Display error details
                if (this.elements.errorDetails && this.paymentData.status === 'failed') {
                    // Clear existing content
                    this.elements.errorDetails.innerHTML = '';
                    
                    // Add error details
                    if (transaction.Status && transaction.Status === 'Failed') {
                        const errorMsg = document.createElement('p');
                        errorMsg.className = 'text-red-700 dark:text-red-300 mb-3';
                        errorMsg.innerHTML = `<span class="font-medium">Payment Failed</span>`;
                        this.elements.errorDetails.appendChild(errorMsg);
                    }
                    
                    // Add transaction details as error info
                    Object.entries(transaction).forEach(([key, value]) => {
                        const row = document.createElement('p');
                        row.className = 'flex justify-between text-sm mb-2';
                        row.innerHTML = `
                            <span class="text-gray-600 dark:text-gray-300">${key}:</span>
                            <span class="text-gray-700 dark:text-gray-200">${value}</span>
                        `;
                        this.elements.errorDetails.appendChild(row);
                    });
                    
                    // Add error code if available
                    if (this.paymentData.error_code) {
                        const errorCode = document.createElement('p');
                        errorCode.className = 'text-gray-600 dark:text-gray-400 text-sm mt-2';
                        errorCode.innerHTML = `Error Code: ${this.paymentData.error_code}`;
                        this.elements.errorDetails.appendChild(errorCode);
                    }
                }
            }
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
