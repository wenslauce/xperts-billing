<?php

namespace App\Services\Integrations\DomainRegistrars;

interface RegistrarInterface
{
    public function checkDomain(string $domain): array;
    public function register(string $domain, int $years = 1, array $contacts = []): array;
    public function renew(string $domain, int $years = 1): array;
    public function getNameservers(string $domain): array;
    public function setNameservers(string $domain, array $nameservers): bool;
}