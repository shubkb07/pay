<div>
    <h1 id="form-title" class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100 text-center">Billing Details</h1>
    
    <p class="text-gray-600 dark:text-gray-400 mb-6 text-center">
        Please provide your billing information to complete your order.
    </p>
    
    <!-- Display form errors -->
    <div id="form-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden" role="alert">
        <span class="block sm:inline"></span>
    </div>
    
    <form id="billing-form" method="post" action="" class="space-y-6">
        <!-- Full Name -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Full Name
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                placeholder="Enter your full name" 
                required
                aria-describedby="name-error"
            >
            <p id="name-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
        </div>

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
        
        <!-- Country and State/Province -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Custom dropdown for country with search -->
            <div class="space-y-2">
                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Country
                </label>
                <div class="custom-select-container relative">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="country-search" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white pr-10" 
                            placeholder="Search country"
                            autocomplete="off"
                        >
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400"></i>
                        </span>
                    </div>
                    <input type="hidden" name="country" id="country" value="">
                    <ul 
                        id="country-dropdown" 
                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 shadow-lg max-h-36 rounded-md border border-gray-300 dark:border-gray-700 hidden overflow-y-auto" 
                        role="listbox"
                    >
                        <!-- Country options will be populated by JavaScript -->
                    </ul>
                </div>
                <p id="country-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
            </div>
            
            <!-- Custom dropdown for state/province with search -->
            <div id="state-container" class="space-y-2 hidden">
                <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    State/Province
                </label>
                <div class="custom-select-container relative">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="state-search" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white pr-10" 
                            placeholder="Search state/province"
                            autocomplete="off"
                            disabled
                        >
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400"></i>
                        </span>
                    </div>
                    <input type="hidden" name="state" id="state" value="">
                    <ul 
                        id="state-dropdown" 
                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 shadow-lg max-h-36 rounded-md border border-gray-300 dark:border-gray-700 hidden overflow-y-auto" 
                        role="listbox"
                    >
                        <!-- State options will be populated by JavaScript -->
                    </ul>
                </div>
                <p id="state-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
            </div>
        </div>
        
        <!-- Phone Number with Country Code -->
        <div class="space-y-2">
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Phone Number
            </label>
            <div class="flex space-x-2">
                <div class="w-1/4 relative">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="phone-code-search" 
                            class="w-full px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white pr-6" 
                            placeholder="+Code"
                            autocomplete="off"
                        >
                        <span class="absolute inset-y-0 right-0 flex items-center pr-1 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-500 dark:text-gray-400"></i>
                        </span>
                    </div>
                    <input type="hidden" name="phonecode" id="phonecode" value="">
                    <ul 
                        id="phone-code-dropdown" 
                        class="absolute z-10 w-64 mt-1 bg-white dark:bg-gray-800 shadow-lg max-h-36 rounded-md border border-gray-300 dark:border-gray-700 hidden overflow-y-auto" 
                        role="listbox"
                    >
                        <!-- Phone code options will be populated by JavaScript -->
                    </ul>
                </div>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                    placeholder="Enter phone number" 
                    required
                    aria-describedby="phone-error"
                >
            </div>
            <p id="phone-error" class="text-red-600 dark:text-red-400 text-sm hidden"></p>
        </div>
        
        <div class="pt-4">
            <button 
                id="billing-submit"
                type="submit" 
                class="w-full bg-blue-400 text-white py-2 px-6 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 cursor-not-allowed"
                disabled
            >
                Continue to Payment
            </button>
        </div>
    </form>
</div>
