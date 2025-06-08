<?php

namespace App\Controllers;

use App\Models\MTenantModel;

class TenantWebsiteController extends BaseController
{
    protected $tenantModel;
    protected $baseDomain = 'smartpricingandpaymentsystem.localhost.com';

    public function __construct()
    {
        $this->tenantModel = new MTenantModel();
    }

    /**
     * Display tenant homepage
     */
    public function index($subdomain)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        return view('tenant_website/index', [
            'tenant' => $tenant
        ]);
    }

    /**
     * Display tenant page
     */
    public function page($subdomain, $page)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        // Check if view exists
        if (file_exists(APPPATH . 'Views/tenant_website/' . $page . '.php')) {
            return view('tenant_website/' . $page, [
                'tenant' => $tenant
            ]);
        }

        return $this->show404();
    }

    /**
     * Serve tenant assets
     */
    public function assets($subdomain, $path)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        // First check tenant-specific assets
        $tenantAssetPath = FCPATH . 'uploads/tenants/' . $tenant['intTenantID'] . '/assets/' . $path;
        if (file_exists($tenantAssetPath)) {
            return $this->response->download($tenantAssetPath, null);
        }

        // Then check shared assets
        $sharedAssetPath = FCPATH . 'assets/' . $path;
        if (file_exists($sharedAssetPath)) {
            return $this->response->download($sharedAssetPath, null);
        }

        return $this->response->setStatusCode(404);
    }

    /**
     * Get tenant by subdomain
     */
    protected function getTenantBySubdomain($subdomain)
    {
        // Remove the base domain from the subdomain if present
        $subdomain = str_replace('.' . $this->baseDomain, '', $subdomain);
        
        return $this->tenantModel->where('txtDomain', $subdomain)
                                ->where('bitActive', 1)
                                ->where('txtStatus', 'active')
                                ->first();
    }

    /**
     * Show 404 error
     */
    protected function show404()
    {
        return view('tenant_website/404');
    }
}
