<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function stripe()
    {
        return view('stripe');
    }

    public function stripePost(Request $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        // Get amount from request and convert to cents
        $amount = $request->amount; // This is in dollars (e.g., 20.00)
        
        // Convert to cents and ensure it's an integer
        $amountInCents = (int) round(floatval($amount) * 100);
        
        if ($amountInCents <= 0) {
            return back()->with('error', 'Invalid amount');
        }

        $charge = $stripe->charges->create([
            'amount' => $amountInCents,
            'currency' => 'usd',
            'source' => $request->stripeToken,
            'description' => 'Payment from Laravel Stripe Example',
        ]);
        
        return back()->with('success', 'Payment successful!');
    }
}

