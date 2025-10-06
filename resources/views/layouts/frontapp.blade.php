<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেমেন্ট প্যাকেজ - Tailwind CSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tiro+Bangla&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
                <div
                    class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    P
                </div>
                <span class="ml-2 text-xl font-bold text-gray-800">পেমেন্ট প্যাকেজ</span>
            </div>

            <!-- রাইট সাইড - মেনু -->
            <nav class="hidden md:flex space-x-6">
                <a href="{{ route('welcome') }}"
                    class="text-gray-600 hover:text-blue-600 transition duration-300">হোম</a>
                <a href="{{ route('stripe') }}" class="text-gray-600 hover:text-blue-600 transition duration-300">Stripe
                    Payment</a>
                <a href="{{ route('paypal') }}" class="text-gray-600 hover:text-blue-600 transition duration-300">Paypal
                    Payment</a>
                <a href="{{ route('geminiai') }}"
                    class="text-gray-600 hover:text-blue-600 transition duration-300">Geminiai</a>
                <a href="{{ route('chatbot.index') }}"
                    class="text-gray-600 hover:text-blue-600 transition duration-300">Chat
                    GPT</a>
                <a href="{{ route('deepseek.chat') }}"
                    class="text-gray-600 hover:text-blue-600 transition duration-300">DeepSeek</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition duration-300">প্যাকেজ</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition duration-300">সেবাসমূহ</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition duration-300">যোগাযোগ</a>
                <a href="#"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">লগইন</a>
            </nav>

            <!-- মোবাইল মেনু বাটন -->
            <button class="md:hidden text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </header>

    <!-- মূল কন্টেন্ট -->
    <main class="container mx-auto px-4 py-12">
        @yield('content')
    </main>

    <!-- ফুটার -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('js')
</body>

</html>