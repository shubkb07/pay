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
        
        // Geo data
        geoData: null,
        
        init() {
            console.log('Initializing create page:', this.data.page);
            
            // Load geo data first (needed for billing page)
            this.loadGeoData().then(() => {
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
            });
        },
        
        // Load geographic data from JSON file
        async loadGeoData() {
            try {
                const response = await fetch('/pay/assets/json/geo.json');
                if (!response.ok) {
                    throw new Error('Failed to load geographic data');
                }
                this.geoData = await response.json();
                console.log('Geo data loaded successfully');
            } catch (error) {
                console.error('Error loading geo data:', error);
                // Fallback to minimal data if couldn't load
                this.geoData = {
                    "US": { "id": "233", "name": "United States", "phonecode": "1", "emojiU": ["U+1F1FA", "U+1F1F8"] },
                    "GB": { "id": "232", "name": "United Kingdom", "phonecode": "44", "emojiU": ["U+1F1EC", "U+1F1E7"] },
                    "IN": { "id": "101", "name": "India", "phonecode": "91", "emojiU": ["U+1F1EE", "U+1F1F3"] }
                };
            }
        },
        
        // Get countries list from geo data
        getCountriesFromGeoData() {
            if (!this.geoData) return [];
            
            return Object.entries(this.geoData).map(([code, data]) => {
                return {
                    code: code,
                    name: data.name,
                    value: code,
                    emojiU: data.emojiU || []
                };
            }).sort((a, b) => a.name.localeCompare(b.name));
        },
        
        // Get states list for a country from geo data
        getStatesFromGeoData(countryCode) {
            if (!this.geoData || !countryCode || !this.geoData[countryCode] || !this.geoData[countryCode].states) {
                return [];
            }
            
            return this.geoData[countryCode].states.map((state, index) => {
                return {
                    code: index.toString(),
                    name: state,
                    value: state
                };
            }).sort((a, b) => a.name.localeCompare(b.name));
        },
        
        // Get phone codes from geo data
        getPhoneCodesFromGeoData() {
            if (!this.geoData) return [];
            
            return Object.entries(this.geoData).map(([code, data]) => {
                const emoji = this.emojiUToString(data.emojiU || []);
                return {
                    code: data.phonecode,
                    name: `${emoji} ${data.name} (+${data.phonecode})`,
                    value: data.phonecode,
                    countryCode: code,
                    countryName: data.name,
                    emojiU: data.emojiU || []
                };
            }).sort((a, b) => a.countryName.localeCompare(b.countryName));
        },
        
        // Convert emoji Unicode points to string
        emojiUToString(emojiU) {
            if (!emojiU || emojiU.length === 0) return '';
            
            try {
                return emojiU.map(u => String.fromCodePoint(parseInt(u.replace('U+', ''), 16))).join('');
            } catch (e) {
                console.error('Error converting emoji unicode:', e);
                return '';
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
            const checkButton = document.getElementById('check-coupon');
            
            // Skip button functionality - just submit form without coupon
            if (skipButton) {
                skipButton.addEventListener('click', () => {
                    couponInput.value = '';
                    this.forms.coupon.submit();
                });
            }
            
            // Check button functionality - verify coupon without submitting
            if (checkButton) {
                checkButton.addEventListener('click', () => {
                    const coupon = couponInput.value.trim();
                    
                    if (!coupon) {
                        couponError.textContent = 'Please enter a coupon code to check.';
                        couponError.classList.remove('hidden');
                        couponSuccess.classList.add('hidden');
                        return;
                    }
                    
                    // Show loading state
                    checkButton.disabled = true;
                    checkButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
                    
                    // Verify the coupon code with the API
                    this.checkCoupon(coupon)
                        .then(data => {
                            checkButton.disabled = false;
                            checkButton.textContent = 'Check';
                            
                            if (data.verify) {
                                couponSuccess.textContent = 'Valid coupon! You can apply it to your order.';
                                couponSuccess.classList.remove('hidden');
                                couponError.classList.add('hidden');
                            } else {
                                couponError.textContent = data.reason || 'This coupon code is not valid.';
                                couponError.classList.remove('hidden');
                                couponSuccess.classList.add('hidden');
                            }
                        })
                        .catch(error => {
                            checkButton.disabled = false;
                            checkButton.textContent = 'Check';
                            couponError.textContent = 'Error checking coupon. Please try again.';
                            couponError.classList.remove('hidden');
                            couponSuccess.classList.add('hidden');
                        });
                });
            }
            
            // Form submission - apply the coupon
            this.forms.coupon.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const coupon = couponInput.value.trim();
                
                // If empty, just submit (same as skip)
                if (coupon === '') {
                    this.forms.coupon.submit();
                    return;
                }
                
                // Verify coupon before submitting
                this.checkCoupon(coupon)
                    .then(data => {
                        if (data.verify) {
                            // Valid coupon, submit the form
                            this.forms.coupon.submit();
                        } else {
                            couponError.textContent = data.reason || 'This coupon is not valid.';
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
            
            // Clear error/success messages when user types
            couponInput.addEventListener('input', () => {
                couponError.classList.add('hidden');
                couponSuccess.classList.add('hidden');
            });
        },
        
        // Check coupon with API
        async checkCoupon(coupon) {
            try {
                const response = await fetch('/api/v1/coupon/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ coupon })
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                return await response.json();
            } catch (error) {
                console.error('Error checking coupon:', error);
                throw error;
            }
        },
        
        // Billing page functionality
        initBillingPage() {
            if (!this.forms.billing) return;
            
            // Initialize filterable dropdowns
            this.initFilterableDropdown('country', this.getCountriesFromGeoData());
            this.initFilterableDropdown('phone-code', this.getPhoneCodesFromGeoData());
            this.initFilterableDropdown('state', []); // Initially empty until country is selected
            
            // Set up country change event to update states
            const countryInput = document.getElementById('country');
            const stateSearchInput = document.getElementById('state-search');
            
            if (countryInput) {
                countryInput.addEventListener('change', () => {
                    const countryCode = countryInput.value;
                    if (countryCode) {
                        const stateOptions = this.getStatesFromGeoData(countryCode);
                        this.updateFilterableDropdown('state', stateOptions);
                        if (stateSearchInput) {
                            stateSearchInput.disabled = false;
                        }
                    } else {
                        this.updateFilterableDropdown('state', []);
                        if (stateSearchInput) {
                            stateSearchInput.disabled = true;
                            stateSearchInput.value = '';
                        }
                    }
                });
            }
            
            // Form validation
            this.forms.billing.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Validate all required fields
                let isValid = true;
                
                // Required fields to validate
                const fieldsToValidate = [
                    { id: 'name', message: 'Full name is required' },
                    { id: 'address1', message: 'Address is required' },
                    { id: 'city', message: 'City is required' },
                    { id: 'postal_code', message: 'Postal code is required' },
                    { id: 'country', message: 'Please select a country' },
                    { id: 'state', message: 'Please select a state/province' },
                    { id: 'phonecode', message: 'Please select a phone code' },
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
                if (phoneInput && phoneInput.value.trim() && !this.isValidPhone(phoneInput.value.trim())) {
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
                    const errorId = input.id.replace('-search', '');
                    const errorElement = document.getElementById(`${errorId}-error`);
                    if (errorElement) errorElement.classList.add('hidden');
                });
            });
        },
        
        // Initialize filterable dropdown
        initFilterableDropdown(id, options) {
            const searchInput = document.getElementById(`${id}-search`);
            const dropdown = document.getElementById(`${id}-dropdown`);
            const hiddenInput = document.getElementById(id.replace('-code', 'code')); // Handle phone-code case
            
            if (!searchInput || !dropdown || !hiddenInput) {
                console.error(`Missing elements for dropdown: ${id}`);
                return;
            }
            
            // Store options for filtering
            searchInput.dataset.options = JSON.stringify(options);
            
            // Populate dropdown with options
            this.updateFilterableDropdown(id, options);
            
            // Show dropdown on focus
            searchInput.addEventListener('focus', () => {
                dropdown.classList.remove('hidden');
            });
            
            // Filter options as user types
            searchInput.addEventListener('input', () => {
                const value = searchInput.value.toLowerCase();
                const allOptions = JSON.parse(searchInput.dataset.options);
                
                // Filter options based on search value
                const filteredOptions = allOptions.filter(option => 
                    option.name.toLowerCase().includes(value)
                );
                
                this.updateFilterableDropdown(id, filteredOptions, false);
                dropdown.classList.remove('hidden');
            });
            
            // Handle click outside to close dropdown
            document.addEventListener('click', (e) => {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
            
            // Handle keyboard navigation
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    dropdown.classList.remove('hidden');
                    const firstItem = dropdown.querySelector('li:not(.hidden)');
                    if (firstItem) firstItem.focus();
                } else if (e.key === 'Escape') {
                    dropdown.classList.add('hidden');
                } else if (e.key === 'Enter' && !dropdown.classList.contains('hidden')) {
                    e.preventDefault();
                    const firstItem = dropdown.querySelector('li:not(.hidden)');
                    if (firstItem) firstItem.click();
                }
            });
        },
        
        // Update filterable dropdown with options
        updateFilterableDropdown(id, options, updateDataset = true) {
            const searchInput = document.getElementById(`${id}-search`);
            const dropdown = document.getElementById(`${id}-dropdown`);
            const hiddenInput = document.getElementById(id.replace('-code', 'code'));
            
            if (!searchInput || !dropdown || !hiddenInput) return;
            
            // Update dataset if required
            if (updateDataset) {
                searchInput.dataset.options = JSON.stringify(options);
            }
            
            // Clear existing options
            dropdown.innerHTML = '';
            
            if (options.length === 0) {
                const emptyItem = document.createElement('li');
                emptyItem.className = 'px-4 py-2 text-gray-500';
                emptyItem.textContent = 'No options available';
                dropdown.appendChild(emptyItem);
                return;
            }
            
            // Add filtered options
            options.forEach(option => {
                const item = document.createElement('li');
                item.setAttribute('role', 'option');
                item.setAttribute('tabindex', '0');
                item.className = 'px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
                item.textContent = option.name;
                item.dataset.value = option.value;
                
                // Handle selection
                item.addEventListener('click', () => {
                    hiddenInput.value = option.value;
                    searchInput.value = option.name;
                    dropdown.classList.add('hidden');
                    
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    hiddenInput.dispatchEvent(event);
                });
                
                // Keyboard handling for each option
                item.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        item.click();
                    }
                });
                
                dropdown.appendChild(item);
            });
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