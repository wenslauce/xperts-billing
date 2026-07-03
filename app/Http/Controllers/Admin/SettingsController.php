<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function payments()
    {
        $settings = Setting::whereIn('key', [
            'payment_mode',
            'stripe_test_key', 'stripe_test_secret', 'stripe_test_webhook_secret',
            'stripe_live_key', 'stripe_live_secret', 'stripe_live_webhook_secret',
            'paystack_test_public_key', 'paystack_test_secret',
            'paystack_live_public_key', 'paystack_live_secret',
        ])->pluck('value', 'key')->toArray();

        return view('admin.settings.payments', compact('settings'));
    }

    public function updatePayments(Request $request)
    {
        $validated = $request->validate([
            'payment_mode' => 'required|in:test,live',
            'stripe_test_key' => 'nullable|string',
            'stripe_test_secret' => 'nullable|string',
            'stripe_test_webhook_secret' => 'nullable|string',
            'stripe_live_key' => 'nullable|string',
            'stripe_live_secret' => 'nullable|string',
            'stripe_live_webhook_secret' => 'nullable|string',
            'paystack_test_public_key' => 'nullable|string',
            'paystack_test_secret' => 'nullable|string',
            'paystack_live_public_key' => 'nullable|string',
            'paystack_live_secret' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            if (! is_null($value)) {
                Setting::setValue($key, $value);
            }
        }

        return redirect()->route('admin.settings.payments')
            ->with('success', 'Payment settings updated successfully.');
    }
}