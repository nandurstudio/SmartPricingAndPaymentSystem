<?php

namespace App\Services;

class CorsService
{
    protected $baseDomain;
    protected $protocol;

    public function __construct()
    {
        $this->baseDomain = getenv('BASE_DOMAIN') ?: 'smartpricingandpaymentsystem.localhost.com';
        $this->protocol = getenv('APP_PROTOCOL') ?: 'http';
    }

    public function getAllowedOrigins(): array
    {
        return [
            $this->protocol . '://' . $this->baseDomain
        ];
    }

    public function getAllowedOriginsPatterns(): array
    {
        return [
            $this->protocol . '://[^.]+\.' . str_replace('.', '\.', $this->baseDomain)
        ];
    }
}
