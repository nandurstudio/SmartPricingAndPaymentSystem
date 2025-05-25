<?php

/**
 * Flash Message Helper Functions
 * 
 * Provides consistent handling of flash messages across the application
 */

if (!function_exists('set_flash_message')) {
    /**
     * Set a flash message that will persist through one redirect
     * Uses both session methods for maximum compatibility
     * 
     * @param string $type    The type of message (success, error, warning, info)
     * @param string $message The message content
     * @return void
     */
    function set_flash_message($type, $message) {
        // Using both methods for maximum compatibility
        session()->setFlashdata($type, $message);
        
        // Also set in raw session for failsafe
        $_SESSION[$type] = $message;
        session()->markAsFlashdata($type);
        
        // Log for debugging
        log_message('debug', "Flash message set ($type): $message");
    }
}

if (!function_exists('get_flash_message')) {
    /**
     * Get a flash message of specified type
     * Tries both session methods
     * 
     * @param string $type The type of message (success, error, warning, info)
     * @return string|null The message or null if none exists
     */
    function get_flash_message($type) {
        // Try both methods
        return session()->getFlashdata($type) ?? ($_SESSION[$type] ?? null);
    }
}

if (!function_exists('has_flash_message')) {
    /**
     * Check if a flash message of specified type exists
     * 
     * @param string $type The type of message (success, error, warning, info)
     * @return bool True if the message exists
     */
    function has_flash_message($type) {
        return session()->has($type) || isset($_SESSION[$type]);
    }
}

if (!function_exists('display_flash_messages')) {
    /**
     * Display all flash messages (success, error, warning, info)
     * with appropriate Bootstrap styling
     * 
     * @return string HTML for the flash messages
     */
    function display_flash_messages() {
        $types = ['success', 'error', 'warning', 'info'];
        $html = '';
        
        foreach ($types as $type) {
            $message = get_flash_message($type);
            if ($message) {
                $bootstrapClass = ($type === 'error') ? 'danger' : $type;
                $icon = '';
                
                // Set appropriate icon
                switch ($type) {
                    case 'success':
                        $icon = '<i class="bi bi-check-circle-fill me-2"></i>';
                        break;
                    case 'error':
                        $icon = '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
                        break;
                    case 'warning':
                        $icon = '<i class="bi bi-exclamation-circle-fill me-2"></i>';
                        break;
                    case 'info':
                        $icon = '<i class="bi bi-info-circle-fill me-2"></i>';
                        break;
                }
                
                $html .= '<div class="alert alert-' . $bootstrapClass . ' alert-dismissible fade show" role="alert">
                    ' . $icon . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
        
        return $html;
    }
}
