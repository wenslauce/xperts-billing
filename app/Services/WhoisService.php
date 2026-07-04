<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhoisService
{
    protected string $baseUrl = 'https://who-dat.as93.net';

    public function lookup(string $domain): array
    {
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        $domain = preg_replace('/\/.*$/', '', $domain);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'User-Agent' => 'XpertsBilling/1.0',
        ])->get("{$this->baseUrl}/{$domain}");

        if (! $response->successful()) {
            return [
                'available' => true,
                'domain' => $domain,
                'error' => $response->status() === 404 ? 'Domain is available!' : 'Could not check domain.',
            ];
        }

        $data = $response->json();

        return [
            'available' => false,
            'domain' => $domain,
            'registrar' => $data['registrar'] ?? 'N/A',
            'created' => $data['created_date'] ?? $data['creation_date'] ?? null,
            'expires' => $data['expires_date'] ?? $data['expiration_date'] ?? null,
            'nameservers' => $data['name_servers'] ?? [],
            'raw' => $data,
        ];
    }

    public function bulkLookup(array $domains): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'User-Agent' => 'XpertsBilling/1.0',
        ])->get("{$this->baseUrl}/multi", [
            'domains' => implode(',', $domains),
        ]);

        if (! $response->successful()) {
            return [];
        }

        return $response->json();
    }

    public function suggestAlternatives(string $domain): array
    {
        $parts = explode('.', $domain, 2);
        $name = $parts[0];
        $tlds = ['.com', '.org', '.net', '.info', '.biz', '.online', '.site', '.co.ke', '.ke'];

        $results = [];
        foreach ($tlds as $tld) {
            $results[] = $this->lookup($name . $tld);
        }

        return $results;
    }
}