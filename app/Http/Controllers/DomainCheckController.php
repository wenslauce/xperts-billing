<?php

namespace App\Http\Controllers;

use App\Services\WhoisService;
use Illuminate\Http\Request;

class DomainCheckController extends Controller
{
    public function __construct(protected WhoisService $whois) {}

    public function check(Request $request)
    {
        $request->validate(['domain' => 'required|string|max:255']);

        $result = $this->whois->lookup($request->domain);
        $alternatives = $this->whois->suggestAlternatives($request->domain);

        if ($request->wantsJson()) {
            return response()->json([
                'result' => $result,
                'alternatives' => $alternatives,
            ]);
        }

        return back()->with('domain_result', $result)->with('domain_alternatives', $alternatives);
    }

    public function hosting()
    {
        return view('hosting');
    }
}