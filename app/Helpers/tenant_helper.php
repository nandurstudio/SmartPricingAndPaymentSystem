<?php

/**
 * Generate tenant website URL
 * 
 * @param string $subdomain The tenant subdomain
 * @param string|null $baseDomain Optional base domain (defaults to app.baseURL or predefined domain)
 * @return string The full tenant website URL
 */
function generate_tenant_url($subdomain) {
    // Get protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    
    // Get base domain from config or default
    $baseDomain = env('BASE_DOMAIN', 'smartpricingandpaymentsystem.localhost.com');
    $baseDomain = rtrim(preg_replace('#^https?://#', '', $baseDomain), '/');
    
    // Generate URL with subdomain
    return $protocol . $subdomain . '.' . $baseDomain;
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
    $baseDomain = env('BASE_DOMAIN', 'smartpricingandpaymentsystem.localhost.com');
    return strpos($host, '.') !== false && strpos($host, $baseDomain) !== false;
}

/**
 * Get current tenant URL base
 * Returns the base URL for tenant subdomains, with optional path appended
 * 
 * @param string $path Path to append to tenant URL
 * @return string Full tenant URL with path
 */
function tenant_url($path = '') {
    // Get current host
    $host = $_SERVER['HTTP_HOST'] ?? '';
    
    // Get protocol
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    
    // Base URL with protocol and host
    $baseURL = $protocol . $host;
    
    // If path provided, append it
    $path = ltrim($path, '/');
    if (!empty($path)) {
        return $baseURL . '/' . $path;
    }
    
    return $baseURL;
}
