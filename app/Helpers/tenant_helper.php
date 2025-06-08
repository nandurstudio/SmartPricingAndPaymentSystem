<?php

/**
 * Generate tenant website URL
 * 
 * @param string $subdomain The tenant subdomain
 * @param string|null $baseDomain Optional base domain (defaults to app.baseURL or predefined domain)
 * @return string The full tenant website URL
 */
function generate_tenant_url($path = '', $baseDomain = null) {
    // Get current protocol and host
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $currentHost = $_SERVER['HTTP_HOST'] ?? '';
    
    // If we're already on a tenant subdomain, just return the relative URL
    if (strpos($currentHost, '.') !== false) {
        return base_url($path);
    }
    
    // Otherwise, need to construct full tenant URL
    if (empty($baseDomain)) {
        $baseDomain = rtrim(preg_replace('#^https?://#', '', env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com'), '/');
    }
    
    // Clean domain and current path
    $baseDomain = rtrim(preg_replace('#^https?://#', '', $baseDomain), '/');
    $path = ltrim($path, '/');
    
    // If this is a subdomain, extract it
    $pathParts = explode('/', $path);
    $subdomain = $pathParts[0];
    $restOfPath = array_slice($pathParts, 1);
    
    // Clean subdomain - only allow letters, numbers, and hyphens
    $subdomain = preg_replace('/[^a-zA-Z0-9-]/', '', $subdomain);
    
    // Build URL
    $url = $protocol . $subdomain . '.' . $baseDomain;
    if (!empty($restOfPath)) {
        $url .= '/' . implode('/', $restOfPath);
    }
    
    return $url;
}

/**
 * Get tenant logo URL
 * 
 * @param string $logo The logo filename
 * @return string The URL to the tenant logo
 */
function get_tenant_logo_url($logo) {
    if (empty($logo)) {
        return '';
    }

    return base_url('uploads/tenants/' . $logo);
}

/**
 * Get tenant CSS URL
 * 
 * @param int $tenantId The tenant ID
 * @return string The URL to the tenant's custom CSS file
 */
function get_tenant_css_url($tenantId) {
    return base_url('uploads/tenants/css/' . $tenantId . '_custom.css');
}

/**
 * Check if current request is on a tenant domain
 * 
 * @return bool True if current domain is a tenant subdomain
 */
function is_tenant_domain() {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    return strpos($host, '.') !== false && 
           $host !== env('app.baseURL');
}

/**
 * Get current tenant URL
 * 
 * @param string $path Path to append to tenant URL
 * @return string Full tenant URL with path
 */
function tenant_url($path = '') {
    $baseURLConfig = config('app.baseURL');
    $baseURL = '';
    if (is_string($baseURLConfig)) {
        $baseURL = $baseURLConfig;
    }
    $baseURL = rtrim($baseURL, '/');
    $path = ltrim($path, '/');
    return $baseURL . '/' . $path;
}
