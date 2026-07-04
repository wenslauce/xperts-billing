<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with ' . $provider);
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if (! $user) {
            $password = Str::random(16);

            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);

            $user->assignRole('customer');

            Customer::create([
                'user_id' => $user->id,
                'phone' => '',
                'country' => 'KE',
                'status' => 'active',
            ]);
        } else {
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}