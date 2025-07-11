<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class TenantFilter implements FilterInterface
{    
    public function before(RequestInterface $request, $arguments = null)
    {
        $host = $request->getServer('HTTP_HOST');
        $baseDomain = env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com';
        $baseDomain = rtrim(preg_replace('#^https?://#', '', $baseDomain), '/');

        // Skip if it's not a tenant subdomain
        if ($host === $baseDomain || !str_contains($host, $baseDomain)) {
            return;
        }

        // Extract subdomain
        $subdomain = str_replace('.' . $baseDomain, '', $host);

        // Load tenant model to verify subdomain
        $tenantModel = model('MTenantModel');
        $tenant = $tenantModel->where('txtDomain', $subdomain)
                            ->where('bitActive', 1)
                            ->where('txtStatus', 'active')
                            ->first();

        if (!$tenant) {
            // Invalid subdomain - show 404
            return service('response')->setStatusCode(404)->setBody(view('tenant_website/404'));
        }

        // Load tenant settings and services
        $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
        $serviceModel = new \App\Models\ServiceModel();
        $services = $serviceModel->where('intTenantID', $tenant['intTenantID'])
                              ->where('bitActive', 1)
                              ->findAll();

        // Store tenant info and services in session
        session()->set('current_tenant', $tenant);
        session()->set('tenant_services', $services);

        // Get the current path
        $path = $request->uri->getPath();

        // Handle different pages for tenant website
        switch ($path) {
            case '/':
                return service('response')->setBody(
                    view('tenant_website/index', [
                        'tenant' => $tenant,
                        'settings' => $settings,
                        'services' => $services
                    ])
                );
            case 'services':
                return service('response')->setBody(
                    view('tenant_website/services', [
                        'tenant' => $tenant,
                        'services' => $services
                    ])
                );
            // Add other paths as needed
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Clean up tenant session if this was a tenant website request
        if (session()->has('current_tenant')) {
            session()->remove('current_tenant');
        }
    }
}
