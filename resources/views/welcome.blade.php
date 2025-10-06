@extends('layouts.frontapp')


@section('content')
<!-- শিরোনাম সেকশন -->
<div class="text-center mb-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">আমাদের পেমেন্ট প্যাকেজসমূহ</h1>
    <p class="text-gray-600 max-w-2xl mx-auto">আপনার প্রয়োজনের জন্য উপযুক্ত প্যাকেজ নির্বাচন করুন। প্রতিটি
        প্যাকেজে রয়েছে বিশেষ সুবিধা এবং সেবা।</p>
</div>

<!-- প্যাকেজ কার্ড গ্রিড -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- প্যাকেজ ১ -->
    @foreach($products as $product)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:-translate-y-2">
        <div class="h-48 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
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

            <button
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                প্যাকেজ কিনুন
            </button>
        </div>
    </div>
    @endforeach
</div>
@endsection