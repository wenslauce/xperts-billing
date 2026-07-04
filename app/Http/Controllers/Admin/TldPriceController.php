<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TldPrice;
use Illuminate\Http\Request;

class TldPriceController extends Controller
{
    public function index(Request $request)
    {
        $query = TldPrice::query();

        if ($request->filled('tld')) {
            $query->where('tld', 'like', '%' . $request->tld . '%');
        }

        if ($request->filled('registrar')) {
            $query->where('registrar', $request->registrar);
        }

        $tldPrices = $query->latest()->paginate(20);
        return view('admin.tld-prices.index', compact('tldPrices'));
    }

    public function create()
    {
        return view('admin.tld-prices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tld' => 'required|string|max:20',
            'registrar' => 'required|string|in:resellerclub,enom,namecheap,godaddy,namesilo,manual',
            'register_price' => 'required|numeric|min:0',
            'renew_price' => 'required|numeric|min:0',
            'transfer_price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        TldPrice::create($validated);

        return redirect()->route('admin.tld-prices.index')
            ->with('success', 'TLD price created successfully.');
    }

    public function edit(TldPrice $tldPrice)
    {
        return view('admin.tld-prices.edit', compact('tldPrice'));
    }

    public function update(Request $request, TldPrice $tldPrice)
    {
        $validated = $request->validate([
            'tld' => 'required|string|max:20',
            'registrar' => 'required|string|in:resellerclub,enom,namecheap,godaddy,namesilo,manual',
            'register_price' => 'required|numeric|min:0',
            'renew_price' => 'required|numeric|min:0',
            'transfer_price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        $tldPrice->update($validated);

        return redirect()->route('admin.tld-prices.index')
            ->with('success', 'TLD price updated successfully.');
    }

    public function destroy(TldPrice $tldPrice)
    {
        $tldPrice->delete();
        return redirect()->route('admin.tld-prices.index')
            ->with('success', 'TLD price deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'tld_prices' => 'required|array',
            'tld_prices.*.id' => 'required|exists:tld_prices,id',
            'tld_prices.*.register_price' => 'required|numeric|min:0',
            'tld_prices.*.renew_price' => 'required|numeric|min:0',
            'tld_prices.*.transfer_price' => 'required|numeric|min:0',
        ]);

        foreach ($validated['tld_prices'] as $data) {
            TldPrice::find($data['id'])->update([
                'register_price' => $data['register_price'],
                'renew_price' => $data['renew_price'],
                'transfer_price' => $data['transfer_price'],
            ]);
        }

        return redirect()->route('admin.tld-prices.index')
            ->with('success', 'TLD prices updated successfully.');
    }
}