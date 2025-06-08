<?php

namespace App\Controllers;

use App\Models\MTenantModel;
use App\Models\ServiceModel;
use App\Models\BookingModel;

class TenantWebsiteController extends BaseController
{    
    protected $tenantModel;
    protected $serviceModel;
    protected $bookingModel;
    protected $baseDomain;
    protected $protocol;
    protected $tenant;

    public function __construct()
    {
        $this->tenantModel = new MTenantModel();
        $this->serviceModel = new ServiceModel();
        $this->bookingModel = new BookingModel();
        $this->baseDomain = env('BASE_DOMAIN') ?: 'smartpricingandpaymentsystem.localhost.com';
        $this->protocol = env('APP_PROTOCOL') ?: 'http';

        // Set the tenant for all requests to this controller
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (strpos($host, '.') !== false) {
            $subdomain = explode('.', $host)[0];
            $this->tenant = $this->tenantModel->where('txtDomain', $subdomain)
                                            ->where('bitActive', 1)
                                            ->where('txtStatus', 'active')
                                            ->first();
            
            if ($this->tenant) {
                // Store tenant in session for use in views
                session()->set('current_tenant', $this->tenant);
            }
        }
    }

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Override base URL for tenant subdomains
        if (is_tenant_domain()) {
            $config = config('App');
            service('url')->setBaseURL(tenant_url());
        }
    }

    /**
     * Display tenant homepage/dashboard
     */
    public function index($subdomain)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        // Get tenant stats
        $stats = [
            'total_services' => $this->serviceModel->where('intTenantID', $tenant['intTenantID'])->countAllResults(),
            'active_services' => $this->serviceModel->where('intTenantID', $tenant['intTenantID'])->where('bitActive', 1)->countAllResults(),
            'total_bookings' => $this->bookingModel->where('intTenantID', $tenant['intTenantID'])->countAllResults(),
            'pending_bookings' => $this->bookingModel->where('intTenantID', $tenant['intTenantID'])->where('txtStatus', 'pending')->countAllResults()
        ];

        return view('tenant_website/dashboard', [
            'tenant' => $tenant,
            'stats' => $stats
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
     * Serve tenant assets with proper CORS headers
     */
    public function assets($subdomain, $path)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        // Get the requesting origin
        $origin = $_SERVER['HTTP_ORIGIN'] ?? null;
        $allowedPattern = '#^' . $this->protocol . '://[^.]+\.' . preg_quote($this->baseDomain) . '$#';

        // Set dynamic CORS headers based on the request origin
        if ($origin && preg_match($allowedPattern, $origin)) {
            $this->response->setHeader('Access-Control-Allow-Origin', $origin);
            $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
            $this->response->setHeader('Access-Control-Allow-Headers', 'Origin, Content-Type');
            $this->response->setHeader('Access-Control-Allow-Credentials', 'true');
            $this->response->setHeader('Vary', 'Origin');
        }

        // Handle OPTIONS preflight request
        if ($this->request->getMethod(true) === 'OPTIONS') {
            return $this->response->setStatusCode(204);
        }

        // First check tenant-specific assets
        $tenantAssetPath = FCPATH . 'uploads/tenants/' . $tenant['intTenantID'] . '/assets/' . $path;
        if (file_exists($tenantAssetPath)) {
            // Set proper content type
            $this->response->setHeader('Content-Type', $this->getMimeType($tenantAssetPath));
            return $this->response->download($tenantAssetPath, null);
        }

        // Then check shared assets
        $sharedAssetPath = FCPATH . 'assets/' . $path;
        if (file_exists($sharedAssetPath)) {
            // Set proper content type
            $this->response->setHeader('Content-Type', $this->getMimeType($sharedAssetPath));
            return $this->response->download($sharedAssetPath, null);
        }

        return $this->response->setStatusCode(404);
    }

    /**
     * Get MIME type for a file
     */
    protected function getMimeType($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        return $mimeTypes[$ext] ?? 'application/octet-stream';
    }    /**
     * Get tenant by subdomain
     */
    protected function getTenantBySubdomain($subdomain)
    {
        // If we already have the tenant from constructor, return it
        if ($this->tenant) {
            return $this->tenant;
        }
        
        // Remove the base domain from the subdomain if present
        $subdomain = str_replace('.' . $this->baseDomain, '', $subdomain);
        
        // Query for tenant and cache it
        $this->tenant = $this->tenantModel->where('txtDomain', $subdomain)
                                         ->where('bitActive', 1)
                                         ->where('txtStatus', 'active')
                                         ->first();
                                         
        if ($this->tenant) {
            session()->set('current_tenant', $this->tenant);
        }
        
        return $this->tenant;
    }

    /**
     * Show 404 error
     */
    protected function show404()
    {
        return view('tenant_website/404');
    }

    /**
     * Handle manifest.json request
     */
    public function manifest($subdomain)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        // Set proper content type for manifest
        $this->response->setHeader('Content-Type', 'application/manifest+json');

        // Generate tenant-specific manifest
        $manifest = [
            'name' => $tenant->business_name ?? 'Smart Booking System',
            'short_name' => $tenant->business_short_name ?? 'SmartBook',
            'description' => 'Multi-tenant booking system for various business types',
            'start_url' => $this->protocol . '://' . $subdomain . '.' . $this->baseDomain . '/',
            'scope' => $this->protocol . '://' . $subdomain . '.' . $this->baseDomain . '/',
            'display' => 'standalone',
            'background_color' => '#2980b9',
            'theme_color' => '#2980b9',
            'icons' => [
                [
                    'src' => $this->protocol . '://' . $subdomain . '.' . $this->baseDomain . '/assets/img/icon-192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => $this->protocol . '://' . $subdomain . '.' . $this->baseDomain . '/assets/img/icon-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ]
        ];

        return $this->response->setJSON($manifest);
    }

    /**
     * Validate tenant origin for CORS
     */
    private function isValidTenantOrigin(string $origin): bool
    {
        // Parse origin URL
        $originParts = parse_url($origin);
        if (!$originParts || !isset($originParts['host'])) {
            return false;
        }

        // Check if origin matches tenant subdomain pattern
        $pattern = '/^[a-zA-Z0-9-]+\.' . preg_quote($this->baseDomain, '/') . '$/';
        return (bool) preg_match($pattern, $originParts['host']);
    }

    /**
     * Display tenant services page
     */
    public function services($subdomain)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        // Get active services for this tenant
        $services = $this->serviceModel->where('intTenantID', $tenant['intTenantID'])
                                     ->where('bitActive', 1)
                                     ->findAll();

        return view('tenant_website/services', [
            'tenant' => $tenant,
            'services' => $services
        ]);
    }

    /**
     * Display tenant bookings page
     */
    public function bookings($subdomain)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        // Get bookings for this tenant
        $bookings = $this->bookingModel->where('intTenantID', $tenant['intTenantID'])
                                      ->findAll();

        return view('tenant_website/bookings', [
            'tenant' => $tenant,
            'bookings' => $bookings
        ]);
    }

    /**
     * Display tenant schedules page
     */
    public function schedules($subdomain)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        return view('tenant_website/schedules', [
            'tenant' => $tenant
        ]);
    }

    /**
     * Display tenant settings page
     */
    public function settings($subdomain)
    {
        $tenant = $this->getTenantBySubdomain($subdomain);
        if (!$tenant) {
            return $this->show404();
        }

        return view('tenant_website/settings', [
            'tenant' => $tenant
        ]);
    }
}
