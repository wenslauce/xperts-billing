<x-admin-layout>
    <x-slot:header>Email Piping Settings</x-slot:header>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <form action="{{ route('admin.settings.email-piping.update') }}" method="POST">
            @csrf
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">IMAP Mailbox Settings</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Configure an IMAP mailbox to automatically create tickets from incoming emails.</p>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">IMAP Host</label><input type="text" name="email_piping_imap_host" value="{{ $settings['email_piping_imap_host'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="imap.example.com"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Port</label><input type="number" name="email_piping_imap_port" value="{{ $settings['email_piping_imap_port'] ?? '993' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Encryption</label>
                        <select name="email_piping_imap_encryption" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="ssl" {{ ($settings['email_piping_imap_encryption'] ?? 'ssl') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="tls" {{ ($settings['email_piping_imap_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="notls" {{ ($settings['email_piping_imap_encryption'] ?? '') == 'notls' ? 'selected' : '' }}>No TLS</option>
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label><input type="text" name="email_piping_imap_username" value="{{ $settings['email_piping_imap_username'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label><input type="password" name="email_piping_imap_password" value="{{ $settings['email_piping_imap_password'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mailbox</label><input type="text" name="email_piping_imap_mailbox" value="{{ $settings['email_piping_imap_mailbox'] ?? 'INBOX' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Processed Folder</label><input type="text" name="email_piping_processed_folder" value="{{ $settings['email_piping_processed_folder'] ?? 'INBOX.Processed' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></div>
                </div>
                <div class="flex justify-end pt-4"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save IMAP Settings</button></div>
            </div>
        </form>
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Email Webhook</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Configure your email service provider to forward incoming emails to this webhook URL.</p>
                <div class="space-y-3">
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Webhook URL</label><code class="block text-sm bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded">{{ url('/webhooks/email') }}</code></div>
                    <form action="{{ route('admin.settings.email-piping.update') }}" method="POST">
                        @csrf
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Webhook Secret (optional)</label><input type="password" name="email_piping_webhook_secret" value="{{ $settings['email_piping_webhook_secret'] ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"><p class="mt-1 text-xs text-gray-500">Used to verify webhook signatures from SendGrid, Mailgun, or Postmark</p></div>
                        <div class="flex justify-end pt-4"><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Webhook Secret</button></div>
                    </form>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">How It Works</h3>
                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <p><strong>IMAP Mode:</strong> Fetches emails every 5 minutes. Matches to existing tickets by <code>[Ticket #123]</code> in subject or creates new tickets.</p>
                    <p><strong>Webhook Mode:</strong> Configure SendGrid Inbound Parse, Mailgun Routes, or Postmark Inbound to forward to the webhook URL above.</p>
                    <p><strong>Department Routing:</strong> Set the email address on each <a href="{{ route('admin.ticket-departments.index') }}" class="text-blue-600 hover:underline">ticket department</a> to auto-route emails.</p>
                    <p><strong>Customer Matching:</strong> Emails are matched to customer accounts by sender email. If no match, the email is ignored.</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>