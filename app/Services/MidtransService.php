<?php

namespace App\Services;

use Config\Midtrans as MidtransConfig;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    protected $config;

    public function __construct()
    {
        $this->config = new MidtransConfig();
        
        // Set Midtrans configuration
        Config::$serverKey = $this->config->serverKey;
        Config::$isProduction = $this->config->isProduction;
        Config::$isSanitized = $this->config->isSanitized;
        Config::$is3ds = $this->config->is3ds;
    }

    /**
     * Create payment token for transaction
     */
    public function createPaymentToken($params)
    {
        // Daftar VA yang memungkinkan free_text (berdasarkan dokumentasi Midtrans)
        $vaWithFreeText = [
            'bca_va' => [
                'inquiry' => [
                    ['en' => 'Please pay using the provided BCA Virtual Account number', 'id' => 'Silakan lakukan pembayaran ke nomor BCA Virtual Account di atas']
                ],
                'payment' => [
                    ['en' => 'Thank you for your payment', 'id' => 'Terima kasih atas pembayaran Anda']
                ]
            ],
            'mandiri_va' => [
                'inquiry' => [
                    ['en' => 'Please pay using the provided Mandiri Virtual Account number', 'id' => 'Silakan lakukan pembayaran ke nomor Mandiri Virtual Account di atas']
                ],
                'payment' => [
                    ['en' => 'Thank you for your payment', 'id' => 'Terima kasih atas pembayaran Anda']
                ]
            ],
            'permata_va' => [
                'inquiry' => [
                    ['en' => 'Please pay using the provided Permata Virtual Account number', 'id' => 'Silakan lakukan pembayaran ke nomor Permata Virtual Account di atas']
                ],
                'payment' => [
                    ['en' => 'Thank you for your payment', 'id' => 'Terima kasih atas pembayaran Anda']
                ]
            ]
        ];
        // Inject free_text ke object VA yang support
        foreach ($vaWithFreeText as $vaKey => $freeText) {
            if ((isset($params['payment_type']) && $params['payment_type'] === 'bank_transfer' && isset($params['bank_transfer']['bank']) && strpos($vaKey, $params['bank_transfer']['bank']) !== false)
                || (isset($params['payment_type']) && $params['payment_type'] === 'echannel' && $vaKey === 'mandiri_va')
                || (isset($params['enabled_payments']) && in_array($vaKey, $params['enabled_payments']))) {
                if (!isset($params[$vaKey])) {
                    $params[$vaKey] = [];
                }
                // Permata VA: tambahkan recipient_name jika belum ada
                if ($vaKey === 'permata_va' && !isset($params[$vaKey]['recipient_name'])) {
                    $params[$vaKey]['recipient_name'] = 'SmartPay System';
                }
                $params[$vaKey]['free_text'] = $freeText;
            }
        }
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return [
                'success' => true,
                'token' => $snapToken
            ];
        } catch (\Exception $e) {
            log_message('error', '[MidtransService::createPaymentToken] Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create payment token: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction status
     */
    public function getStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return [
                'success' => true,
                'data' => $status
            ];
        } catch (\Exception $e) {
            log_message('error', '[MidtransService::getStatus] Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to get transaction status: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Handle payment notification from Midtrans
     */
    public function handleNotification($notification)
    {
        try {
            $notif = json_decode($notification, true);
            
            $transaction = $notif['transaction_status'];
            $type = $notif['payment_type'];
            $orderId = $notif['order_id'];
            $fraud = $notif['fraud_status'];

            $status = null;
            
            if ($transaction == 'capture') {
                // Credit card payment
                if ($type == 'credit_card') {
                    if($fraud == 'challenge') {
                        $status = 'challenge';
                    } else {
                        $status = 'success';
                    }
                }
            }
            else if ($transaction == 'settlement') {
                $status = 'success';
            }
            else if($transaction == 'pending') {
                $status = 'pending';
            }
            else if ($transaction == 'deny') {
                $status = 'deny';
            }
            else if ($transaction == 'expire') {
                $status = 'expire';
            }
            else if ($transaction == 'cancel') {
                $status = 'cancel';
            }

            return [
                'success' => true,
                'order_id' => $orderId,
                'status' => $status
            ];

        } catch (\Exception $e) {
            log_message('error', '[MidtransService::handleNotification] Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to process notification: ' . $e->getMessage()
            ];
        }
    }
}
