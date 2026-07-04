<?php

namespace App\Services\Integrations\DomainRegistrars;

use Illuminate\Support\Facades\Http;

class NameSiloClient implements RegistrarInterface
{
    protected string $apiKey;
    protected string $endpoint;
    protected bool $testMode;

    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? '';
        $this->endpoint = $config['endpoint'] ?? 'https://www.namesilo.com/api';
        $this->testMode = $config['test_mode'] ?? true;
    }

    protected function baseParams(): array
    {
        return ['version' => 1, 'type' => 'json', 'key' => $this->apiKey];
    }

    protected function get(string $command, array $params = []): array
    {
        $response = Http::get("{$this->endpoint}/{$command}", array_merge($this->baseParams(), $params));
        if (! $response->successful()) return ['status' => 'FAILURE', 'detail' => 'API request failed'];
        return $response->json() ?? ['status' => 'FAILURE', 'detail' => 'Invalid response'];
    }

    public function checkDomain(string $domain): array
    {
        $data = $this->get('checkRegisterDomain', ['domain' => $domain]);
        $reply = $data['reply'] ?? [];
        return ['available' => ($reply['available'] ?? 'no') === 'yes', 'domain' => $domain, 'status' => $reply['available'] ?? 'no', 'price' => $reply['price'] ?? null];
    }

    public function register(string $domain, int $years = 1, array $contacts = []): array
    {
        $params = ['domain' => $domain, 'years' => $years, 'fn' => $contacts['first_name'] ?? '', 'ln' => $contacts['last_name'] ?? '', 'ad' => $contacts['address'] ?? '', 'cy' => $contacts['city'] ?? '', 'st' => $contacts['state'] ?? '', 'zp' => $contacts['postal_code'] ?? '', 'ct' => $contacts['country'] ?? 'KE', 'em' => $contacts['email'] ?? '', 'ph' => $contacts['phone'] ?? ''];
        if (! empty($contacts['nameservers'])) {
            foreach ($contacts['nameservers'] as $i => $ns) $params['ns' . ($i + 1)] = $ns;
        } else {
            $params['ns1'] = 'ns1.xpertsafrica.com'; $params['ns2'] = 'ns2.xpertsafrica.com';
        }
        $data = $this->get('registerDomain', $params);
        $reply = $data['reply'] ?? [];
        return ['success' => ($reply['status'] ?? 'FAILURE') === 'SUCCESS', 'domain' => $domain, 'order_id' => $reply['order_id'] ?? null, 'raw' => $data];
    }

    public function renew(string $domain, int $years = 1): array
    {
        $data = $this->get('renewDomain', ['domain' => $domain, 'years' => $years]);
        $reply = $data['reply'] ?? [];
        return ['success' => ($reply['status'] ?? 'FAILURE') === 'SUCCESS', 'domain' => $domain, 'order_id' => $reply['order_id'] ?? null, 'raw' => $data];
    }

    public function getNameservers(string $domain): array
    {
        $data = $this->get('getDomainInfo', ['domain' => $domain]);
        $reply = $data['reply'] ?? [];
        $ns = [];
        foreach (['ns1', 'ns2', 'ns3', 'ns4', 'ns5', 'ns6', 'ns7', 'ns8', 'ns9', 'ns10', 'ns11', 'ns12', 'ns13'] as $key) {
            if (! empty($reply[$key])) $ns[] = $reply[$key];
        }
        return $ns;
    }

    public function setNameservers(string $domain, array $nameservers): bool
    {
        $params = ['domain' => $domain];
        foreach ($nameservers as $i => $ns) $params['ns' . ($i + 1)] = $ns;
        $data = $this->get('changeNameServers', $params);
        $reply = $data['reply'] ?? [];
        return ($reply['status'] ?? 'FAILURE') === 'SUCCESS';
    }
}