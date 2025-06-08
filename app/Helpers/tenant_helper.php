<?php

/**
 * Generate tenant website URL
 * 
 * @param string $subdomain The tenant subdomain
 * @param string|null $baseDomain Optional base domain (defaults to app.baseURL or predefined domain)
 * @return string The full tenant website URL
 */
function generate_tenant_url($subdomain, $baseDomain = null) {
    if (empty($subdomain)) {
        return '';
    }

    if ($baseDomain === null) {
        // Get base domain, remove http/https and trailing slashes
        $baseDomain = rtrim(preg_replace('#^https?://#', '', env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com'), '/');
    }

    // Clean the base domain
    $baseDomain = preg_replace('#^https?://#', '', $baseDomain);
    $baseDomain = rtrim($baseDomain, '/');

    // Clean subdomain - only allow letters, numbers, and hyphens
    $subdomain = preg_replace('/[^a-zA-Z0-9-]/', '', $subdomain);
    
    // Remove any domain parts from subdomain
    $subdomain = explode('.', $subdomain)[0];

    // Build the URL
    return 'http://' . $subdomain . '.' . $baseDomain;
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
