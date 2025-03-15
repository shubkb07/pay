import {app} from './app.js';

/**
 * Payment Creation System
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize the create page functionality
    const createPage = {
        // Page data from CDATA
        data: window.pageData || {},
        
        // Form elements
        forms: {
            email: document.getElementById('email-form'),
            coupon: document.getElementById('coupon-form'),
            billing: document.getElementById('billing-form')
        },
        
        // Country and state data for dropdowns
        countries: [
            { code: 'US', name: 'United States' },
            { code: 'CA', name: 'Canada' },
            { code: 'UK', name: 'United Kingdom' },
            { code: 'IN', name: 'India' },
            { code: 'AU', name: 'Australia' },
            { code: 'DE', name: 'Germany' },
            { code: 'FR', name: 'France' },
            { code: 'JP', name: 'Japan' }
        ],
        
        states: {
            'US': [
                { code: 'AL', name: 'Alabama' },
                { code: 'AK', name: 'Alaska' },
                { code: 'AZ', name: 'Arizona' },
                { code: 'AR', name: 'Arkansas' },
                { code: 'CA', name: 'California' },
                { code: 'CO', name: 'Colorado' },
                { code: 'CT', name: 'Connecticut' },
                { code: 'DE', name: 'Delaware' },
                { code: 'FL', name: 'Florida' },
                { code: 'GA', name: 'Georgia' }
                // Add more as needed
            ],
            'CA': [
                { code: 'AB', name: 'Alberta' },
                { code: 'BC', name: 'British Columbia' },
                { code: 'MB', name: 'Manitoba' },
                { code: 'NB', name: 'New Brunswick' },
                { code: 'NL', name: 'Newfoundland and Labrador' },
                { code: 'NS', name: 'Nova Scotia' },
                { code: 'ON', name: 'Ontario' },
                { code: 'PE', name: 'Prince Edward Island' },
                { code: 'QC', name: 'Quebec' },
                { code: 'SK', name: 'Saskatchewan' }
            ],
            'IN': [
                { code: 'AP', name: 'Andhra Pradesh' },
                { code: 'AR', name: 'Arunachal Pradesh' },
                { code: 'AS', name: 'Assam' },
                { code: 'BR', name: 'Bihar' },
                { code: 'CT', name: 'Chhattisgarh' },
                { code: 'GA', name: 'Goa' },
                { code: 'GJ', name: 'Gujarat' },
                { code: 'HR', name: 'Haryana' },
                { code: 'HP', name: 'Himachal Pradesh' },
                { code: 'JH', name: 'Jharkhand' }
                // Add more as needed
            ]
        },
        
        init() {
            console.log('Initializing create page:', this.data.page);
            
            // Initialize the appropriate page based on data
            switch (this.data.page) {
                case 'email':
                    this.initEmailPage();
                    break;
                case 'coupon':
                    this.initCouponPage();
                    break;
                case 'billing':
                    this.initBillingPage();
                    break;
            }
        },
        
        // Email page functionality
        initEmailPage() {
            if (!this.forms.email) return;
            
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            
            this.forms.email.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Validate email
                const email = emailInput.value.trim();
                
                // Check if email contains a plus sign (alias email)
                if (email.includes('+')) {
                    emailError.textContent = 'Alias emails with "+" are not allowed.';
                    emailError.classList.remove('hidden');
                    return;
                }
                
                // Check if email is valid
                if (!this.isValidEmail(email)) {
                    emailError.textContent = 'Please enter a valid email address.';
                    emailError.classList.remove('hidden');
                    return;
                }
                
                // Submit the form if validation passes
                emailError.classList.add('hidden');
                this.forms.email.submit();
            });
            
            // Clear error message when user types
            emailInput.addEventListener('input', () => {
                emailError.classList.add('hidden');
            });
        },
        
        // Coupon page functionality
        initCouponPage() {
            if (!this.forms.coupon) return;
            
            const couponInput = document.getElementById('coupon');
            const couponError = document.getElementById('coupon-error');
            const couponSuccess = document.getElementById('coupon-success');
            const skipButton = document.getElementById('skip-coupon');
            
            // Skip button functionality - just submit form without coupon
            if (skipButton) {
                skipButton.addEventListener('click', () => {
                    couponInput.value = '';
                    this.forms.coupon.submit();
                });
            }
            
            this.forms.coupon.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Validate coupon
                const coupon = couponInput.value.trim();
                
                // If empty, just submit (same as skip)
                if (coupon === '') {
                    this.forms.coupon.submit();
                    return;
                }
                
                // Here you would normally check the coupon validity via AJAX
                // Mock validation for demonstration
                this.validateCoupon(coupon)
                    .then(result => {
                        if (result.valid) {
                            couponSuccess.textContent = 'Coupon applied: ' + result.message;
                            couponSuccess.classList.remove('hidden');
                            couponError.classList.add('hidden');
                            
                            // Submit form after a brief delay to show success message
                            setTimeout(() => {
                                this.forms.coupon.submit();
                            }, 1500);
                        } else {
                            couponError.textContent = 'Invalid coupon: ' + result.message;
                            couponError.classList.remove('hidden');
                            couponSuccess.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        couponError.textContent = 'Error validating coupon. Please try again.';
                        couponError.classList.remove('hidden');
                        couponSuccess.classList.add('hidden');
                    });
            });
            
            // Clear error message when user types
            couponInput.addEventListener('input', () => {
                couponError.classList.add('hidden');
                couponSuccess.classList.add('hidden');
            });
        },
        
        // Mock coupon validation - in real implementation, this would make an AJAX call
        validateCoupon(code) {
            return new Promise((resolve, reject) => {
                // Simulate API call delay
                setTimeout(() => {
                    // Simple mock validation logic
                    if (!code || code.length < 3) {
                        resolve({ valid: false, message: 'Coupon code is too short' });
                    } else if (code.toLowerCase() === 'expired') {
                        resolve({ valid: false, message: 'This coupon has expired' });
                    } else if (code.toLowerCase() === 'invalid') {
                        resolve({ valid: false, message: 'This coupon code is not valid' });
                    } else {
                        resolve({ valid: true, message: 'Discount applied' });
                    }
                }, 500);
            });
        },
        
        // Billing page functionality
        initBillingPage() {
            if (!this.forms.billing) return;
            
            // Initialize custom dropdowns
            this.initCustomDropdown('country', this.countries);
            this.initCustomDropdown('state', []); // Initially empty until country is selected
            
            // Set up country change event to update states
            const countryInput = document.getElementById('country');
            if (countryInput) {
                countryInput.addEventListener('change', () => {
                    const countryCode = countryInput.value;
                    const stateOptions = this.states[countryCode] || [];
                    this.updateCustomDropdown('state', stateOptions);
                });
            }
            
            // Form validation
            this.forms.billing.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Validate all required fields
                let isValid = true;
                
                // Required fields to validate
                const fieldsToValidate = [
                    { id: 'address1', message: 'Address is required' },
                    { id: 'city', message: 'City is required' },
                    { id: 'postal_code', message: 'Postal code is required' },
                    { id: 'country', message: 'Please select a country' },
                    { id: 'state', message: 'Please select a state/province' },
                    { id: 'phone', message: 'Phone number is required' }
                ];
                
                // Clear all previous errors
                fieldsToValidate.forEach(field => {
                    const errorElement = document.getElementById(`${field.id}-error`);
                    if (errorElement) errorElement.classList.add('hidden');
                });
                
                // Check each field
                fieldsToValidate.forEach(field => {
                    const inputElement = document.getElementById(field.id);
                    if (!inputElement || !inputElement.value.trim()) {
                        this.showError(field.id, field.message);
                        isValid = false;
                    }
                });
                
                // Phone validation
                const phoneInput = document.getElementById('phone');
                if (phoneInput && !this.isValidPhone(phoneInput.value.trim())) {
                    this.showError('phone', 'Please enter a valid phone number');
                    isValid = false;
                }
                
                // Submit if all valid
                if (isValid) {
                    this.forms.billing.submit();
                }
            });
            
            // Add input event listeners to clear errors
            const formInputs = this.forms.billing.querySelectorAll('input');
            formInputs.forEach(input => {
                input.addEventListener('input', () => {
                    const errorElement = document.getElementById(`${input.id}-error`);
                    if (errorElement) errorElement.classList.add('hidden');
                });
            });
        },
        
        // Initialize custom dropdown
        initCustomDropdown(id, options) {
            const button = document.getElementById(`${id}-button`);
            const dropdown = document.getElementById(`${id}-dropdown`);
            const hiddenInput = document.getElementById(id);
            const selectedValue = button.querySelector(`.${id}-selected-value`);
            
            if (!button || !dropdown || !hiddenInput) return;
            
            // Populate dropdown options
            this.updateCustomDropdown(id, options);
            
            // Toggle dropdown on button click
            button.addEventListener('click', () => {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                
                // Toggle dropdown visibility
                dropdown.classList.toggle('hidden');
                button.setAttribute('aria-expanded', !isExpanded);
                
                // Add focus trap if opened
                if (!isExpanded) {
                    const firstItem = dropdown.querySelector('li');
                    if (firstItem) firstItem.focus();
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    button.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Handle keyboard navigation
            dropdown.addEventListener('keydown', (e) => {
                const items = dropdown.querySelectorAll('li');
                const currentIndex = Array.from(items).findIndex(item => item === document.activeElement);
                
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        if (currentIndex < items.length - 1) {
                            items[currentIndex + 1].focus();
                        }
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        if (currentIndex > 0) {
                            items[currentIndex - 1].focus();
                        } else {
                            button.focus();
                        }
                        break;
                    case 'Escape':
                        e.preventDefault();
                        dropdown.classList.add('hidden');
                        button.setAttribute('aria-expanded', 'false');
                        button.focus();
                        break;
                    case 'Enter':
                    case ' ':
                        e.preventDefault();
                        document.activeElement.click();
                        break;
                }
            });
        },
        
        // Update custom dropdown options
        updateCustomDropdown(id, options) {
            const dropdown = document.getElementById(`${id}-dropdown`);
            const hiddenInput = document.getElementById(id);
            const selectedValue = document.querySelector(`.${id}-selected-value`);
            const button = document.getElementById(`${id}-button`);
            
            if (!dropdown || !hiddenInput || !selectedValue || !button) return;
            
            // Clear existing options
            dropdown.innerHTML = '';
            
            // Add new options
            options.forEach(option => {
                const item = document.createElement('li');
                item.setAttribute('role', 'option');
                item.setAttribute('tabindex', '-1');
                item.className = 'px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
                item.textContent = option.name;
                item.setAttribute('data-value', option.code);
                
                // Handle option selection
                item.addEventListener('click', () => {
                    hiddenInput.value = option.code;
                    selectedValue.textContent = option.name;
                    dropdown.classList.add('hidden');
                    button.setAttribute('aria-expanded', 'false');
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    hiddenInput.dispatchEvent(event);
                    
                    // Focus back to button after selection
                    button.focus();
                });
                
                dropdown.appendChild(item);
            });
            
            // Add empty message if no options
            if (options.length === 0) {
                const emptyItem = document.createElement('li');
                emptyItem.className = 'px-4 py-2 text-gray-500';
                emptyItem.textContent = 'No options available';
                dropdown.appendChild(emptyItem);
                
                // Reset hidden input
                hiddenInput.value = '';
                selectedValue.textContent = `Select ${id.charAt(0).toUpperCase() + id.slice(1)}`;
            }
        },
        
        // Show error message for a field
        showError(fieldId, message) {
            const errorElement = document.getElementById(`${fieldId}-error`);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        },
        
        // Email validation
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        // Phone validation
        isValidPhone(phone) {
            // Allow various formats with or without country code
            const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,4}[-\s.]?[0-9]{1,9}$/;
            return phoneRegex.test(phone);
        }
    };
    
    // Initialize the page
    createPage.init();
});