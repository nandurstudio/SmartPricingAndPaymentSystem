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
        // Gunakan hanya metode resmi CodeIgniter untuk flashdata
        session()->setFlashdata($type, $message);
        
        // Log for debugging
        log_message('debug', "Flash message set ($type): $message");
    }
}

if (!function_exists('get_flash_message')) {
    /**
     * Get a flash message of specified type
     * Tries flashdata first, then regular session data as fallback
     * 
     * @param string $type The type of message (success, error, warning, info)
     * @return string|null The message or null if none exists
     */
    function get_flash_message($type) {
        // Cek dalam urutan: flashdata -> session data -> raw session
        $message = session()->getFlashdata($type);
        if ($message !== null) {
            return $message;
        }

        $message = session()->get($type);
        if ($message !== null) {
            return $message;
        }

        // Fallback ke $_SESSION jika ada
        if (isset($_SESSION[$type]) && isset($_SESSION['__ci_vars'][$type])) {
            return $_SESSION[$type];
        }

        return null;
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
        return session()->has($type) || 
               (isset($_SESSION[$type]) && isset($_SESSION['__ci_vars'][$type]));
    }
}

if (!function_exists('display_flash_messages')) {
    /**
     * Display all flash messages (success, error, warning, info)
     * with appropriate Bootstrap styling
     * After display, the messages will be automatically cleared by CodeIgniter
     *
     * @return string HTML for the flash messages
     */
    function display_flash_messages() {
        $types = ['success', 'error', 'warning', 'info'];
        $html = '';
        foreach ($types as $type) {
            $message = get_flash_message($type);
            if ($message) {
                if ($type === 'error') {
                    // Untuk pesan error, gunakan div yang sudah ada
                    $html .= "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var errorDiv = document.getElementById('error-message');
                            var errorText = document.getElementById('error-text');
                            if (errorDiv && errorText) {
                                errorDiv.style.display = 'block';
                                errorText.textContent = " . json_encode(htmlspecialchars($message)) . ";
                            }
                        });
                    </script>";
                } else {
                    // Untuk tipe pesan lain, gunakan alert bootstrap biasa
                    $bootstrapClass = $type;
                    $icon = '';
                    switch ($type) {
                        case 'success': $icon = '<i class="bi bi-check-circle-fill me-2"></i>'; break;
                        case 'warning': $icon = '<i class="bi bi-exclamation-circle-fill me-2"></i>'; break;
                        case 'info':    $icon = '<i class="bi bi-info-circle-fill me-2"></i>'; break;
                    }
                    $html .= '<div class="alert alert-' . $bootstrapClass . ' alert-dismissible fade show" role="alert">'
                        . $icon . htmlspecialchars($message) .
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }

                log_message('debug', "Displaying flash message ($type): $message");
            }
        }
        return $html;
    }
}
