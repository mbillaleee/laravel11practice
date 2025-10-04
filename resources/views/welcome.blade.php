<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেমেন্ট প্যাকেজ - Tailwind CSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tiro+Bangla&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tiro Bangla', serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- হেডার সেকশন -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- লেফট সাইড - লোগো -->
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    P
                </div>
                <span class="ml-2 text-xl font-bold text-gray-800">পেমেন্ট প্যাকেজ</span>
            </div>
            
            <!-- রাইট সাইড - মেনু -->
            <nav class="hidden md:flex space-x-6">
                <a href="#" class="text-gray-600 hover:text-blue-600 transition duration-300">হোম</a>
                <a href="{{ route('stripe') }}" class="text-gray-600 hover:text-blue-600 transition duration-300">Stripe Payment</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition duration-300">প্যাকেজ</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition duration-300">সেবাসমূহ</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition duration-300">যোগাযোগ</a>
                <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">লগইন</a>
            </nav>
            
            <!-- মোবাইল মেনু বাটন -->
            <button class="md:hidden text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </header>

    <!-- মূল কন্টেন্ট -->
    <main class="container mx-auto px-4 py-12">
        <!-- শিরোনাম সেকশন -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">আমাদের পেমেন্ট প্যাকেজসমূহ</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">আপনার প্রয়োজনের জন্য উপযুক্ত প্যাকেজ নির্বাচন করুন। প্রতিটি প্যাকেজে রয়েছে বিশেষ সুবিধা এবং সেবা।</p>
        </div>
        
        <!-- প্যাকেজ কার্ড গ্রিড -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- প্যাকেজ ১ -->
             @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:-translate-y-2">
                <div class="h-48 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-800">{{ $product->name }}</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">জনপ্রিয়</span>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $product->detail }}</p>
                    <div class="mb-6">
                        <span class="text-3xl font-bold text-gray-800">৳ {{ $product->price }}</span>
                        <span class="text-gray-500">/মাস</span>
                    </div>
                   
                    <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                        প্যাকেজ কিনুন
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <!-- ফুটার -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                            P
                        </div>
                        <span class="ml-2 text-lg font-bold">পেমেন্ট প্যাকেজ</span>
                    </div>
                    <p class="mt-2 text-gray-400">আপনার ব্যবসার জন্য সেরা পেমেন্ট সমাধান</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300">গোপনীয়তা নীতি</a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300">সেবার শর্তাবলী</a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300">যোগাযোগ</a>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-6 pt-6 text-center text-gray-400">
                <p>© ২০২৩ পেমেন্ট প্যাকেজ। সকল অধিকার সংরক্ষিত।</p>
            </div>
        </div>
    </footer>
</body>
</html>