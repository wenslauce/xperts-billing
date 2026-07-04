<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer.user', 'items.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%");
        }

        $orders = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer.user', 'items.product', 'items.pricing', 'invoice']);
        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $customers = \App\Models\Customer::with('user')->latest()->get();
        $products = \App\Models\Product::where('is_active', true)->with('pricing')->get();
        return view('admin.orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'pricing_id' => 'required|exists:pricing,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $customer = \App\Models\Customer::find($validated['customer_id']);
        $product = \App\Models\Product::find($validated['product_id']);
        $pricing = \App\Models\Pricing::find($validated['pricing_id']);

        $order = \App\Models\Order::create([
            'customer_id' => $validated['customer_id'],
            'status' => 'awaiting_payment',
            'total' => $pricing->price * $validated['quantity'],
            'currency' => $pricing->currency,
            'notes' => $validated['notes'] ?? null,
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $validated['product_id'],
            'pricing_id' => $validated['pricing_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $pricing->price,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order created successfully. Invoice generated.');
    }
}
