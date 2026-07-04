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

    public function registrars()
    {
        $prefixes = ['resellerclub', 'enom', 'namecheap', 'godaddy', 'namesilo'];
        $settings = [];

        foreach ($prefixes as $prefix) {
            $base = "registrar_{$prefix}_";
            $settings[$prefix] = [
                'api_key' => Setting::getValue("{$base}api_key", ''),
                'api_secret' => Setting::getValue("{$base}api_secret", ''),
                'api_user_id' => Setting::getValue("{$base}api_user_id", ''),
                'api_endpoint' => Setting::getValue("{$base}api_endpoint", ''),
                'test_mode' => Setting::getValue("{$base}test_mode", 'true'),
            ];
        }

        return view('admin.settings.registrars', compact('settings'));
    }

    public function updateRegistrars(Request $request)
    {
        $prefixes = ['resellerclub', 'enom', 'namecheap', 'godaddy', 'namesilo'];

        $rules = [];
        foreach ($prefixes as $prefix) {
            $base = "registrar_{$prefix}_";
            $rules["{$base}api_key"] = 'nullable|string';
            $rules["{$base}api_secret"] = 'nullable|string';
            $rules["{$base}api_user_id"] = 'nullable|string';
            $rules["{$base}api_endpoint"] = 'nullable|string';
            $rules["{$base}test_mode"] = 'nullable|in:true,false';
        }

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if (! is_null($value)) {
                Setting::setValue($key, $value);
            }
        }

        return redirect()->route('admin.settings.registrars')
            ->with('success', 'Registrar settings updated successfully.');
    }

    public function emailPiping()
    {
        $settings = Setting::whereIn('key', [
            'email_piping_imap_host',
            'email_piping_imap_port',
            'email_piping_imap_encryption',
            'email_piping_imap_username',
            'email_piping_imap_password',
            'email_piping_imap_mailbox',
            'email_piping_processed_folder',
            'email_piping_webhook_secret',
        ])->pluck('value', 'key')->toArray();

        return view('admin.settings.email-piping', compact('settings'));
    }

    public function updateEmailPiping(Request $request)
    {
        $validated = $request->validate([
            'email_piping_imap_host' => 'nullable|string|max:255',
            'email_piping_imap_port' => 'nullable|integer|min:1|max:65535',
            'email_piping_imap_encryption' => 'nullable|in:ssl,tls,notls',
            'email_piping_imap_username' => 'nullable|string|max:255',
            'email_piping_imap_password' => 'nullable|string',
            'email_piping_imap_mailbox' => 'nullable|string|max:255',
            'email_piping_processed_folder' => 'nullable|string|max:255',
            'email_piping_webhook_secret' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            if (! is_null($value)) {
                Setting::setValue($key, $value);
            }
        }

        return redirect()->route('admin.settings.email-piping')
            ->with('success', 'Email piping settings updated successfully.');
    }
}