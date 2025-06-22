<?php

if (!function_exists('safe_base_url')) {
    /**
     * Generate a safe base URL without double slashes
     * 
     * @param string $path Optional path to append to base URL
     * @return string The full URL without double slashes
     */
    function safe_base_url($path = '') {
        $baseUrl = rtrim(base_url(), '/');
        $path = ltrim($path, '/');
        return $path ? $baseUrl . '/' . $path : $baseUrl;
    }
}
