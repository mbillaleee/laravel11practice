<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .StripeElement {
            background-color: white;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            transition: all 0.3s;
        }
        
        .StripeElement--focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .StripeElement--invalid {
            border-color: #ef4444;
        }
        
        .StripeElement--complete {
            border-color: #10b981;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Header with logo -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-2xl text-purple-600"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white">Secure Payment</h1>
            <p class="text-purple-200 mt-2">Complete your purchase safely</p>
        </div>

        <!-- Payment Card -->
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i> Order Summary
                </h2>
                <p class="text-blue-100 text-sm mt-1">Review your order details</p>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Payment Form -->
                <form id="payment-form" action="{{ route('stripe.post') }}" method="POST">
                    @csrf

                    <div class="mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-300">
                            <div>
                                <h3 class="font-semibold text-gray-800">Example Product</h3>
                                <p class="text-gray-500 text-sm">Premium subscription</p>
                            </div>
                            <div class="text-right">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">$</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        name="amount" 
                                        id="amount"
                                        value="10.00"
                                        step="0.01"
                                        min="1"
                                        max="10000"
                                        class="w-28 pl-8 pr-3 py-2 border border-gray-300 rounded-lg text-right font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 bg-white"
                                        onchange="updatePaymentAmount()"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Quantity</span>
                            <span class="font-medium">1</span>
                        </div>
                        
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="subtotal">$10.00</span>
                        </div>
                        
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium">$0.00</span>
                        </div>
                        
                        <div class="flex justify-between mt-4 pt-3 border-t border-gray-300">
                            <span class="text-lg font-bold text-gray-800">Total</span>
                            <span class="text-lg font-bold text-gray-800" id="total">$10.00</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-1 text-blue-500"></i> Credit or Debit Card
                        </label>
                        <div id="card-element" class="StripeElement"></div>
                        <div id="card-errors" class="text-red-500 text-sm mt-2 flex items-center" role="alert">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <span id="error-text"></span>
                        </div>
                    </div>

                    <input type="hidden" name="stripeToken" id="stripeToken">

                    <!-- Security Badge -->
                    <div class="flex items-center justify-center mb-4 text-sm text-gray-500 bg-green-50 rounded-lg p-3 border border-green-200">
                        <i class="fas fa-lock mr-2 text-green-500"></i>
                        <span>Your payment is secure and encrypted</span>
                    </div>
                    
                    <div class="flex items-center justify-center mb-4 text-sm text-gray-500 bg-green-50 rounded-lg p-3 border border-green-200">
                        <i class="fas fa-home mr-2 text-green-500"></i>
                        <span><a href="{{ route('welcome') }}">Home</a></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-button" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl">
                        <i class="fas fa-lock mr-2"></i>
                        Pay <span id="pay-amount">$10.00</span>
                    </button>
                </form>
            </div>
            
            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                        <span>Secured by Stripe</span>
                    </div>
                    <div class="flex space-x-3 text-xl">
                        <i class="fab fa-cc-visa text-blue-600"></i>
                        <i class="fab fa-cc-mastercard text-red-500"></i>
                        <i class="fab fa-cc-amex text-blue-400"></i>
                        <i class="fab fa-cc-discover text-orange-500"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-6 text-center text-white text-sm">
            <p>Need help? <a href="#" class="underline hover:text-purple-200 transition duration-200">Contact our support team</a></p>
        </div>
    </div>

    <script>
        // Function to update payment amount in real-time
        function updatePaymentAmount() {
            const amountInput = document.getElementById('amount');
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            const payAmountElement = document.getElementById('pay-amount');
            
            let amount = parseFloat(amountInput.value) || 0;
            
            // Ensure minimum amount
            if (amount < 1) {
                amount = 1;
                amountInput.value = '1.00';
            }
            
            // Format currency
            const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2
            });
            
            const formattedAmount = formatter.format(amount);
            
            subtotalElement.textContent = formattedAmount;
            totalElement.textContent = formattedAmount;
            payAmountElement.textContent = formattedAmount;
        }

        // Initialize Stripe
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        
        const card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                    fontFamily: 'system-ui, -apple-system, sans-serif',
                },
            },
        });
        
        card.mount('#card-element');
        
        const form = document.getElementById('payment-form');
        const errorElement = document.getElementById('card-errors');
        const errorText = document.getElementById('error-text');
        const submitButton = document.getElementById('submit-button');
        
        // Real-time validation
        card.addEventListener('change', function(event) {
            if (event.error) {
                errorElement.classList.remove('hidden');
                errorText.textContent = event.error.message;
                submitButton.disabled = true;
                submitButton.classList.remove('from-blue-500', 'to-purple-600', 'hover:from-blue-600', 'hover:to-purple-700', 'hover:shadow-xl');
                submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            } else {
                errorElement.classList.add('hidden');
                errorText.textContent = '';
                submitButton.disabled = false;
                submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitButton.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-purple-600', 'hover:from-blue-600', 'hover:to-purple-700', 'hover:shadow-xl');
            }
        });
        
        // Form submission
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Get current amount
            const amount = document.getElementById('amount').value;
            const payAmountElement = document.getElementById('pay-amount');
            
            // Disable submit button to prevent multiple submissions
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    errorElement.classList.remove('hidden');
                    errorText.textContent = result.error.message;
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-lock mr-2"></i> Pay ' + payAmountElement.textContent;
                } else {
                    document.getElementById('stripeToken').value = result.token.id;
                    form.submit();
                }
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePaymentAmount();
        });
    </script>
</body>
</html>