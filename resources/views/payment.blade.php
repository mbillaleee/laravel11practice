<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Payment</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #0070ba 0%, #1546a0 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .paypal-blue {
            background-color: #0070ba;
        }
        .paypal-blue:hover {
            background-color: #005ea6;
        }
        .paypal-dark-blue {
            background-color: #1546a0;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <i class="fab fa-paypal text-3xl text-blue-600"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white">Secure PayPal Payment</h1>
            <p class="text-blue-200 mt-2">Fast, safe and easy way to pay</p>
        </div>

        <!-- Payment Card -->
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white">
                <h2 class="text-xl font-bold flex items-center justify-center">
                    <i class="fab fa-paypal mr-3"></i> Complete Your Payment
                </h2>
                <p class="text-blue-100 text-sm mt-1 text-center">Enter amount and proceed to PayPal</p>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Payment Form -->
                <form action="{{ route('paypal.payment') }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <!-- Amount Input Section -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-dollar-sign mr-2 text-blue-500"></i> 
                            Enter Amount (USD)
                        </label>
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">$</span>
                            </div>
                            <input 
                                type="number" 
                                name="amount" 
                                id="amount"
                                required 
                                step="0.01" 
                                min="0.01"
                                max="10000"
                                value="10.00"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-lg font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="0.00"
                                oninput="updateAmountPreview()"
                            >
                        </div>
                        
                        <!-- Amount Preview -->
                        <div class="mt-3 bg-blue-50 rounded-lg p-3 border border-blue-200">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">You will pay:</span>
                                <span class="text-lg font-bold text-blue-600" id="amountPreview">$10.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-2">Quick Select:</p>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="setAmount(5.00)" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200 text-sm font-medium">
                                $5.00
                            </button>
                            <button type="button" onclick="setAmount(10.00)" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200 text-sm font-medium">
                                $10.00
                            </button>
                            <button type="button" onclick="setAmount(20.00)" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200 text-sm font-medium">
                                $20.00
                            </button>
                            <button type="button" onclick="setAmount(50.00)" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200 text-sm font-medium">
                                $50.00
                            </button>
                            <button type="button" onclick="setAmount(100.00)" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200 text-sm font-medium">
                                $100.00
                            </button>
                            <button type="button" onclick="setAmount(500.00)" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200 text-sm font-medium">
                                $500.00
                            </button>
                        </div>
                    </div>

                    <!-- Security Features -->
                    <div class="mb-6 bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-500 text-lg mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-green-800 text-sm">Secure Payment</h4>
                                <p class="text-green-600 text-xs mt-1">Your payment information is encrypted and secure. We never share your details.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-bold py-4 px-4 rounded-lg transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fab fa-paypal mr-3 text-xl"></i>
                        Pay <span id="payButtonAmount">$10.00</span> with PayPal
                    </button>
                </form>
            </div>
            
            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-lock mr-2 text-green-500"></i>
                        <span>256-bit SSL Secured</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-globe mr-2 text-blue-500"></i>
                        <span>Global Payments</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center text-white text-sm">
            <p>Need help? <a href="#" class="underline hover:text-blue-200 transition duration-200">Contact our support team</a></p>
            <p class="mt-2 text-blue-200">All major credit cards accepted through PayPal</p>
        </div>
    </div>

    <script>
        // Function to update amount preview
        function updateAmountPreview() {
            const amountInput = document.getElementById('amount');
            const amountPreview = document.getElementById('amountPreview');
            const payButtonAmount = document.getElementById('payButtonAmount');
            
            let amount = parseFloat(amountInput.value) || 0;
            
            // Ensure minimum amount
            if (amount < 0.01) {
                amount = 0.01;
                amountInput.value = '0.01';
            }
            
            // Format currency
            const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2
            });
            
            const formattedAmount = formatter.format(amount);
            
            amountPreview.textContent = formattedAmount;
            payButtonAmount.textContent = formattedAmount;
        }

        // Function to set quick amount
        function setAmount(amount) {
            const amountInput = document.getElementById('amount');
            amountInput.value = amount.toFixed(2);
            updateAmountPreview();
            
            // Add visual feedback
            const buttons = document.querySelectorAll('button[type="button"]');
            buttons.forEach(btn => {
                if (btn.textContent.includes(`$${amount.toFixed(2)}`)) {
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                    btn.classList.add('bg-blue-100', 'text-blue-700', 'border', 'border-blue-300');
                    
                    // Remove highlight after 1 second
                    setTimeout(() => {
                        btn.classList.remove('bg-blue-100', 'text-blue-700', 'border', 'border-blue-300');
                        btn.classList.add('bg-gray-100', 'text-gray-700');
                    }, 1000);
                }
            });
        }

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const amountInput = document.getElementById('amount');
            const amount = parseFloat(amountInput.value);
            
            if (amount < 0.01) {
                e.preventDefault();
                alert('Please enter an amount of at least $0.01');
                amountInput.focus();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateAmountPreview();
        });
    </script>
</body>
</html>