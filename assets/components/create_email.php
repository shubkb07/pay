<div class="text-center">
    <h1 id="form-title" class="text-2xl md:text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Enter Your Email</h1>
    
    <p class="text-gray-600 dark:text-gray-400 mb-6">
        Please provide your email address to continue with your payment.
    </p>
    
    <form id="email-form" method="post" action="" class="space-y-6">
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">
                Email Address
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                placeholder="your@email.com" 
                required
                aria-describedby="email-error"
            >
            <p id="email-error" class="text-red-600 dark:text-red-400 text-sm hidden text-left"></p>
        </div>
        
        <div>
            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                Continue
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-sm text-gray-500 dark:text-gray-400">
        <p>We'll use your email to send payment receipts and updates.</p>
    </div>
</div>
