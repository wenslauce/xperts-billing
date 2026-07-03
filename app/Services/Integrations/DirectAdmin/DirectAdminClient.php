<?php

namespace App\Services\Integrations\DirectAdmin;

use App\Models\Server;
use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DirectAdminClient
{
    protected string $baseUrl;
    protected string $loginKey;
    protected string $resellerUsername;

    public function __construct(protected Server $server)
    {
        $this->baseUrl = "https://{$server->hostname}:2222";
        $this->loginKey = $server->api_key;
        $this->resellerUsername = $server->api_username;
    }

    /**
     * Get Basic auth header.
     * DirectAdmin Login Key auth: use the key as the username, password can be anything.
     */
    protected function authHeader(): string
    {
        return 'Basic ' . base64_encode("{$this->loginKey}:");
    }

    /**
     * Test connection to the DirectAdmin server.
     */
    public function testConnection(): bool
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
        ])->get("{$this->baseUrl}/CMD_API_SHOW_RESELLER_PACKAGES", []);

        return $response->successful();
    }

    /**
     * Create a new end-user hosting account under the reseller.
     */
    public function createUser(Service $service): array
    {
        $customer = $service->customer;
        $product = $service->product;
        $username = $this->generateUsername($customer);

        $payload = [
            'action' => 'create',
            'add' => 'Submit',
            'username' => $username,
            'email' => $customer->user->email,
            'passwd' => Str::random(16),
            'passwd2' => Str::random(16),
            'domain' => $service->domain,
            'package' => $product->directadmin_package,
            'ip' => 'shared',
            'notify' => 'no',
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
        ])->asForm()->post("{$this->baseUrl}/CMD_API_ACCOUNT_USER", $payload);

        $body = $response->body();

        if (! $response->successful()) {
            return [
                'success' => false,
                'error' => $body,
            ];
        }

        // Check for DirectAdmin error in response
        if (preg_match('/error=/i', $body)) {
            return [
                'success' => false,
                'error' => $body,
            ];
        }

        return [
            'success' => true,
            'username' => $username,
            'password' => $payload['passwd'],
            'raw' => $body,
        ];
    }

    /**
     * Suspend a user account.
     */
    public function suspendUser(string $username): bool
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
        ])->asForm()->post("{$this->baseUrl}/CMD_API_SELECT_USERS", [
            'location' => 'CMD_SUSPEND_USER',
            'select0' => $username,
            'suspend' => 'Suspend',
        ]);

        return $response->successful();
    }

    /**
     * Unsuspend a user account.
     */
    public function unsuspendUser(string $username): bool
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
        ])->asForm()->post("{$this->baseUrl}/CMD_API_SELECT_USERS", [
            'location' => 'CMD_UNSUSPEND_USER',
            'select0' => $username,
            'unsuspend' => 'Unsuspend',
        ]);

        return $response->successful();
    }

    /**
     * Terminate (delete) a user account.
     */
    public function terminateUser(string $username): bool
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
        ])->asForm()->post("{$this->baseUrl}/CMD_API_SELECT_USERS", [
            'location' => 'CMD_DELETE_USER',
            'select0' => $username,
            'confirmed' => 'Confirm',
            'delete' => 'Delete',
        ]);

        return $response->successful();
    }

    /**
     * Get usage info for a user account.
     */
    public function getUserUsage(string $username): ?array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->authHeader(),
        ])->get("{$this->baseUrl}/CMD_API_SHOW_USER_USAGE", [
            'user' => $username,
        ]);

        if (! $response->successful()) {
            return null;
        }

        parse_str($response->body(), $parsed);
        return $parsed;
    }

    /**
     * Generate a unique DirectAdmin username from customer data.
     */
    protected function generateUsername($customer): string
    {
        $base = strtolower(preg_replace('/[^a-z0-9]/', '', substr($customer->user->name, 0, 6)));
        $suffix = str_pad($customer->id, 3, '0', STR_PAD_LEFT);
        $username = $base . $suffix;

        // Ensure max 8 chars for DirectAdmin
        return substr($username, 0, 8);
    }
}