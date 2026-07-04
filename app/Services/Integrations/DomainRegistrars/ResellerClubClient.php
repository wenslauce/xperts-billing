<?php

namespace App\Services\Integrations\DomainRegistrars;

use Illuminate\Support\Facades\Http;

class ResellerClubClient implements RegistrarInterface
{
    protected string $apiKey;
    protected string $userId;
    protected string $endpoint;
    protected bool $testMode;

    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? '';
        $this->userId = $config['user_id'] ?? '';
        $this->endpoint = $config['endpoint'] ?? 'https://test.httpapi.com/api';
        $this->testMode = $config['test_mode'] ?? true;
    }

    protected function baseParams(): array
    {
        return [
            'auth-userid' => $this->userId,
            'api-key' => $this->apiKey,
        ];
    }

    protected function get(string $command, array $params = []): array
    {
        $response = Http::get("{$this->endpoint}/{$command}.json", array_merge($this->baseParams(), $params));

        if (! $response->successful()) {
            return ['status' => 'FAILURE', 'detail' => 'API request failed'];
        }

        return $response->json() ?? ['status' => 'FAILURE', 'detail' => 'Invalid response'];
    }

    public function checkDomain(string $domain): array
    {
        $data = $this->get('domains/available', [
            'domain-name' => $domain,
            'tlds' => explode('.', $domain)[1] ?? 'com',
        ]);

        $status = $data[$domain] ?? [];

        return [
            'available' => ($status['status'] ?? '') === 'available',
            'domain' => $domain,
            'status' => $status['status'] ?? 'unknown',
            'price' => $status['price'] ?? null,
        ];
    }

    public function register(string $domain, int $years = 1, array $contacts = []): array
    {
        $parts = explode('.', $domain, 2);
        $sld = $parts[0];
        $tld = $parts[1] ?? 'com';

        $params = [
            'domain-name' => $sld,
            'tld' => $tld,
            'years' => $years,
            'ns' => $contacts['nameservers'] ?? ['ns1.xpertsafrica.com', 'ns2.xpertsafrica.com'],
            'customer-id' => $contacts['customer_id'] ?? '',
            'reg-contact-id' => $contacts['reg_contact_id'] ?? '',
            'admin-contact-id' => $contacts['admin_contact_id'] ?? '',
            'tech-contact-id' => $contacts['tech_contact_id'] ?? '',
            'billing-contact-id' => $contacts['billing_contact_id'] ?? '',
            'invoice-option' => 'NoInvoice',
            'protect-privacy' => false,
        ];

        $data = $this->get('domains/register', $params);

        return [
            'success' => isset($data['status']) && $data['status'] === 'Success',
            'domain' => $domain,
            'order_id' => $data['orderid'] ?? null,
            'raw' => $data,
        ];
    }

    public function renew(string $domain, int $years = 1): array
    {
        $data = $this->get('domains/renew', [
            'domain-name' => $domain,
            'years' => $years,
            'invoice-option' => 'NoInvoice',
        ]);

        return [
            'success' => isset($data['status']) && $data['status'] === 'Success',
            'domain' => $domain,
            'order_id' => $data['orderid'] ?? null,
            'raw' => $data,
        ];
    }

    public function getNameservers(string $domain): array
    {
        $data = $this->get('domains/details', ['domain-name' => $domain]);
        $ns = $data['nameservers'] ?? [];

        return is_array($ns) ? $ns : [];
    }

    public function setNameservers(string $domain, array $nameservers): bool
    {
        $data = $this->get('domains/modify-ns', [
            'domain-name' => $domain,
            'ns' => $nameservers,
        ]);

        return isset($data['status']) && $data['status'] === 'Success';
    }
}