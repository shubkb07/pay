<div class="text-center">
    <h1 id="form-title" class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Apply Coupon</h1>
    
    <p class="text-gray-600 dark:text-gray-400 mb-6">
        Enter a coupon code if you have one, or continue to payment.
    </p>
    
    <form id="coupon-form" method="post" action="" class="space-y-6">
        <div class="space-y-2">
            <label for="coupon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">
                Coupon Code (Optional)
            </label>
            <div class="flex space-x-2">
                <input 
                    type="text" 
                    id="coupon" 
                    name="coupon" 
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                    placeholder="Enter coupon code"
                    aria-describedby="coupon-error coupon-success"
                >
                <button 
                    type="button" 
                    id="check-coupon" 
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                    Check
                </button>
            </div>
            <p id="coupon-error" class="text-red-600 dark:text-red-400 text-sm hidden text-left"></p>
            <p id="coupon-success" class="text-green-600 dark:text-green-400 text-sm hidden text-left"></p>
        </div>
        
        <div class="flex space-x-4">
            <button 
                type="button" 
                id="skip-coupon" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 py-2 px-4 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                Skip
            </button>
            
            <button 
                type="submit" 
                id="apply-coupon" 
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                Apply
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
        <p>You can continue without entering a coupon code.</p>
    </div>
</div>
