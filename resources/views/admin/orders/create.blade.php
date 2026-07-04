<x-admin-layout>
    <x-slot:header>Create Order</x-slot:header>
    <form action="{{ route('admin.orders.store') }}" method="POST" class="max-w-2xl">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
                <select name="customer_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->user->name }} ({{ $customer->user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
                <select name="product_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" id="product-select">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ ucfirst(str_replace('_', ' ', $product->type)) }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pricing Tier</label>
                <select name="pricing_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" id="pricing-select">
                    <option value="">Select Product First</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                <input type="number" name="quantity" value="1" min="1" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Order</button>
            </div>
        </div>
    </form>
</x-admin-layout>

<script>
document.getElementById('product-select').addEventListener('change', function() {
    const productId = this.value;
    const pricingSelect = document.getElementById('pricing-select');
    pricingSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (productId) {
        fetch('/admin/api/products/' + productId + '/pricing')
            .then(response => response.json())
            .then(data => {
                pricingSelect.innerHTML = '<option value="">Select Pricing</option>';
                data.forEach(pricing => {
                    const option = document.createElement('option');
                    option.value = pricing.id;
                    option.textContent = `${pricing.billing_cycle} - ${number_format(pricing.price, 2)} ${pricing.currency}`;
                    pricingSelect.appendChild(option);
                });
            });
    } else {
        pricingSelect.innerHTML = '<option value="">Select Product First</option>';
    }
});

function number_format(number, decimals = 2) {
    return number.toFixed(decimals).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
</script>