<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Customer;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::with('customer.user')->latest()->paginate(10);
        return view('admin.domains.index', compact('domains'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        return view('admin.domains.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'registrar' => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:registration_date',
            'auto_renew' => 'boolean',
            'status' => 'required|in:active,expired,transferred,cancelled',
        ]);

        Domain::create($validated);

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain added successfully.');
    }

    public function edit(Domain $domain)
    {
        $customers = Customer::with('user')->get();
        return view('admin.domains.edit', compact('domain', 'customers'));
    }

    public function update(Request $request, Domain $domain)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'registrar' => 'nullable|string|max:255',
            'registration_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:registration_date',
            'auto_renew' => 'boolean',
            'status' => 'required|in:active,expired,transferred,cancelled',
        ]);

        $domain->update($validated);

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain deleted.');
    }
}