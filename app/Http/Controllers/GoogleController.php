<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        // select_account দিয়ে অ্যাকাউন্ট পিকার শো, prompt=consent চাইলে রিফ্রেশ কনসেন্ট
        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        // Socialite v5+ এ stateless দরকার হলে ->stateless() ইউজ করবেন
        $googleUser = Socialite::driver('google')->user();

        // Socialite ফিল্ডস: id, name, email, avatar, user['given_name'], user['family_name'] ইত্যাদি
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'              => $googleUser->getName() ?: $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => now(), // Google ইমেইল ভেরিফাইড ধরে নিচ্ছি
                // পাসওয়ার্ড দরকার নেই, তবু fill করতে হলে র‍্যান্ডম
                'password'          => bcrypt(Str::random(32)),
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}