<?php

namespace App\Services\Integrations\DomainRegistrars;

use App\Models\Setting;
use InvalidArgumentException;

class RegistrarServiceProvider
{
    /**
     * Map of registrar names to their client classes.
     */
    protected array $registrars = [
        'resellerclub' => ResellerClubClient::class,
        'enom'         => EnomClient::class,
        'namecheap'    => NamecheapClient::class,
        'godaddy'      => GoDaddyClient::class,
        'namesilo'     => NameSiloClient::class,
    ];

    /**
     * Resolve a registrar client by name.
     */
    public function make(string $registrar): RegistrarInterface
    {
        $registrar = strtolower($registrar);

        if (! isset($this->registrars[$registrar])) {
            throw new InvalidArgumentException("Unsupported registrar: {$registrar}");
        }

        $class = $this->registrars[$registrar];
        $config = $this->getConfig($registrar);

        return new $class($config);
    }

    /**
     * Get all available registrar names.
     */
    public function getAvailableRegistrars(): array
    {
        return array_keys($this->registrars);
    }

    /**
     * Get all registrar display names.
     */
    public function getRegistrarDisplayNames(): array
    {
        return [
            'resellerclub' => 'ResellerClub',
            'enom'         => 'Enom',
            'namecheap'    => 'Namecheap',
            'godaddy'      => 'GoDaddy',
            'namesilo'     => 'NameSilo',
        ];
    }

    /**
     * Load configuration for a registrar from the settings table.
     */
    protected function getConfig(string $registrar): array
    {
        $prefix = "registrar_{$registrar}_";
        $keys = [
            "{$prefix}api_key",
            "{$prefix}api_secret",
            "{$prefix}api_user_id",
            "{$prefix}api_endpoint",
            "{$prefix}test_mode",
        ];

        $settings = Setting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        return [
            'api_key'     => $settings["{$prefix}api_key"] ?? '',
            'api_secret'  => $settings["{$prefix}api_secret"] ?? '',
            'api_user_id' => $settings["{$prefix}api_user_id"] ?? '',
            'endpoint'    => $settings["{$prefix}api_endpoint"] ?? $this->getDefaultEndpoint($registrar),
            'test_mode'   => ($settings["{$prefix}test_mode"] ?? 'true') === 'true',
        ];
    }

    /**
     * Get the default API endpoint for a registrar.
     */
    protected function getDefaultEndpoint(string $registrar): string
    {
        return match ($registrar) {
            'resellerclub' => 'https://test.httpapi.com/api',
            'enom'         => 'https://api.enom.com',
            'namecheap'    => 'https://api.namecheap.com/xml.response',
            'godaddy'      => 'https://api.godaddy.com/v1',
            'namesilo'     => 'https://www.namesilo.com/api',
            default        => '',
        };
    }
}