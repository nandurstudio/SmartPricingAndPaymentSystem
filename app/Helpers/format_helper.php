<?php

/**
 * Format currency with IDR format
 * 
 * @param float $amount The amount to format
 * @param string $currency The currency code (default: IDR)
 * @return string Formatted currency amount
 */
if (!function_exists('format_currency')) {
    function format_currency($amount, $currency = 'IDR') {
        return $currency . ' ' . number_format($amount, 0, ',', '.');
    }
}

/**
 * Get color class for booking status
 * 
 * @param string $status The booking status
 * @return string The Bootstrap color class
 */
if (!function_exists('get_booking_status_color')) {
    function get_booking_status_color($status) {
        return match ($status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'rescheduled' => 'primary',
            default => 'secondary'
        };
    }
}
