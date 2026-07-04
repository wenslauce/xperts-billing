<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Order;
use App\Models\Domain;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('company_name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|size:2',
            'tax_id' => 'nullable|string|max:50',
            'billing_address_line1' => 'nullable|string|max:255',
            'billing_address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('customer');

        $customer = Customer::create([
            'user_id' => $user->id,
            'company_name' => $validated['company_name'] ?? null,
            'phone' => $validated['phone'] ?? '',
            'country' => $validated['country'] ?? 'KE',
            'tax_id' => $validated['tax_id'] ?? null,
            'billing_address_line1' => $validated['billing_address_line1'] ?? null,
            'billing_address_line2' => $validated['billing_address_line2'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load('user');
        $services = Service::where('customer_id', $customer->id)->with('product')->latest()->get();
        $invoices = Invoice::where('customer_id', $customer->id)->latest()->take(10)->get();
        $orders = Order::where('customer_id', $customer->id)->latest()->take(10)->get();
        $domains = Domain::where('customer_id', $customer->id)->latest()->get();
        $tickets = Ticket::where('customer_id', $customer->id)->latest()->take(10)->get();
        $totalPaid = \App\Models\Transaction::whereHas('invoice', fn($q) => $q->where('customer_id', $customer->id))->where('status', 'succeeded')->sum('amount');
        $unpaidBalance = Invoice::where('customer_id', $customer->id)->whereIn('status', ['unpaid', 'overdue'])->sum('total');

        return view('admin.customers.show', compact('customer', 'services', 'invoices', 'orders', 'domains', 'tickets', 'totalPaid', 'unpaidBalance'));
    }

    public function edit(Customer $customer)
    {
        $customer->load('user');
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->user_id,
            'password' => 'nullable|string|min:8',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|size:2',
            'tax_id' => 'nullable|string|max:50',
            'billing_address_line1' => 'nullable|string|max:255',
            'billing_address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|in:active,locked',
        ]);

        $userData = ['name' => $validated['name'], 'email' => $validated['email']];
        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $customer->user->update($userData);

        $customer->update([
            'company_name' => $validated['company_name'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'tax_id' => $validated['tax_id'],
            'billing_address_line1' => $validated['billing_address_line1'],
            'billing_address_line2' => $validated['billing_address_line2'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function impersonate(Customer $customer)
    {
        auth()->login($customer->user);
        return redirect()->route('customer.dashboard')
            ->with('success', 'Logged in as ' . $customer->user->name);
    }
}