<div>
    <h1 id="form-title" class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100 text-center">Billing Details</h1>
    
    <p class="text-gray-600 dark:text-gray-400 mb-6 text-center">
        Please provide your billing information to complete your order.
    </p>
    
    <form id="billing-form" method="post" action="" class="space-y-6">
        <!-- Address Line 1 -->
        <div class="space-y-2">
            <label for="address1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Address Line 1
            </label>
            <input 
                type="text" 
                id="address1" 
                name="address1" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                placeholder="Street address, P.O. box, etc." 
                required
                aria-describedby="address1-error"
            >
            <p id="address1-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
        </div>
        
        <!-- Address Line 2 -->
        <div class="space-y-2">
            <label for="address2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Address Line 2 <span class="text-gray-500">(Optional)</span>
            </label>
            <input 
                type="text" 
                id="address2" 
                name="address2" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                placeholder="Apartment, suite, unit, etc."
            >
        </div>
        
        <!-- City and ZIP/Postal Code -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    City
                </label>
                <input 
                    type="text" 
                    id="city" 
                    name="city" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                    required
                    aria-describedby="city-error"
                >
                <p id="city-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
            </div>
            
            <div class="space-y-2">
                <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    ZIP/Postal Code
                </label>
                <input 
                    type="text" 
                    id="postal_code" 
                    name="postal_code" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                    required
                    aria-describedby="postal_code-error"
                >
                <p id="postal_code-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
            </div>
        </div>
        
        <!-- State/Province and Country -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Custom dropdown for state/province -->
            <div class="space-y-2">
                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    State/Province
                </label>
                <div class="custom-select-container relative">
                    <button 
                        type="button" 
                        id="state-button"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                        aria-haspopup="listbox" 
                        aria-expanded="false"
                        aria-labelledby="state-label"
                    >
                        <span class="state-selected-value text-gray-700 dark:text-gray-200">Select State/Province</span>
                        <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400"></i>
                    </button>
                    <input type="hidden" name="state" id="state" value="">
                    <ul 
                        id="state-dropdown" 
                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md border border-gray-300 dark:border-gray-700 hidden overflow-auto" 
                        role="listbox"
                    >
                        <!-- State options will be populated by JavaScript -->
                    </ul>
                </div>
                <p id="state-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
            </div>
            
            <!-- Custom dropdown for country -->
            <div class="space-y-2">
                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Country
                </label>
                <div class="custom-select-container relative">
                    <button 
                        type="button" 
                        id="country-button"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                        aria-haspopup="listbox" 
                        aria-expanded="false"
                        aria-labelledby="country-label"
                    >
                        <span class="country-selected-value text-gray-700 dark:text-gray-200">Select Country</span>
                        <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400"></i>
                    </button>
                    <input type="hidden" name="country" id="country" value="">
                    <ul 
                        id="country-dropdown" 
                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md border border-gray-300 dark:border-gray-700 hidden overflow-auto" 
                        role="listbox"
                    >
                        <!-- Country options will be populated by JavaScript -->
                    </ul>
                </div>
                <p id="country-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
            </div>
        </div>
        
        <!-- Phone Number -->
        <div class="space-y-2">
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Phone Number
            </label>
            <input 
                type="tel" 
                id="phone" 
                name="phone" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                placeholder="For delivery-related communications" 
                required
                aria-describedby="phone-error"
            >
            <p id="phone-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
        </div>
        
        <div class="pt-4">
            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                Continue to Payment
            </button>
        </div>
    </form>
</div>
