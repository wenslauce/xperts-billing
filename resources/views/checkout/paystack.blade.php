<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment - {{ config('app.name') }}</title>
    <script src="https://js.paystack.co/v1/inline.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Processing Payment</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Invoice: {{ $invoice->invoice_number }}<br>
                Amount: {{ number_format($invoice->total, 2) }} {{ $invoice->currency }}
            </p>
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Redirecting to payment gateway...</p>
        </div>
    </div>

    <script>
        const handler = PaystackPop.setup({
            key: '{{ $publicKey }}',
            email: '{{ $email }}',
            amount: {{ $amount }},
            ref: '{{ $reference }}',
            currency: '{{ $invoice->currency }}',
            metadata: {
                invoice_id: {{ $invoice->id }},
                invoice_number: '{{ $invoice->invoice_number }}'
            },
            callback: function(response) {
                window.location.href = '{{ route('paystack.callback') }}?reference=' + response.reference;
            },
            onClose: function() {
                window.location.href = '{{ route('checkout.cancel', ['invoice' => $invoice->id]) }}';
            }
        });
        handler.openIframe();
    </script>
</body>
</html>