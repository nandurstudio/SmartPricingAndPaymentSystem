<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class PaymentController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Handle payment callback from payment gateway
     * @return ResponseInterface
     */
    public function handleCallback()
    {
        $json = $this->request->getJSON();
        log_message('info', 'Payment callback received: ' . json_encode($json));

        // Verify the signature/authenticity of the callback
        if (!$this->verifyCallback($json)) {
            return $this->response->setStatusCode(401)
                ->setJSON(['status' => 'error', 'message' => 'Invalid signature']);
        }

        try {
            $this->db->transBegin();

            // Update payment status in the database
            $orderId = $json->order_id ?? null;
            $status = $json->transaction_status ?? null;
            $paymentType = $json->payment_type ?? null;
            $fraudStatus = $json->fraud_status ?? null;

            if (!$orderId || !$status) {
                throw new \Exception('Invalid callback data');
            }

            // Update the payment record
            $this->updatePaymentStatus($orderId, $status, $paymentType, $json);

            // If payment is successful, update related records
            if ($this->isPaymentSuccess($status, $fraudStatus)) {
                $this->handleSuccessfulPayment($orderId);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Callback processed successfully'
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Payment callback error: ' . $e->getMessage());

            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Error processing callback'
                ]);
        }
    }

    /**
     * Verify the authenticity of the callback
     */
    private function verifyCallback($data): bool
    {
        // TODO: Implement signature verification based on your payment gateway
        // Example for a typical HMAC verification:
        /*
        $signature = $this->request->getHeader('X-Callback-Signature');
        $secretKey = getenv('PAYMENT_SECRET_KEY');
        $generatedSignature = hash_hmac('sha256', json_encode($data), $secretKey);
        return hash_equals($signature, $generatedSignature);
        */
        
        // For development, return true
        return true;
    }

    /**
     * Update payment status in the database
     */
    private function updatePaymentStatus(string $orderId, string $status, string $paymentType, object $rawData): void
    {
        $this->db->table('tr_payment')
            ->where('txtOrderID', $orderId)
            ->update([
                'txtStatus' => $status,
                'txtPaymentType' => $paymentType,
                'txtRawResponse' => json_encode($rawData),
                'dtmUpdatedDate' => date('Y-m-d H:i:s'),
                'txtUpdatedBy' => 'SYSTEM_CALLBACK'
            ]);
    }

    /**
     * Check if payment is successful based on status
     */
    private function isPaymentSuccess(string $status, ?string $fraudStatus): bool
    {
        $successStatuses = ['settlement', 'capture'];
        return in_array(strtolower($status), $successStatuses) 
            && (!$fraudStatus || $fraudStatus === 'accept');
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment(string $orderId): void
    {
        // Update booking status
        $this->db->table('tr_booking')
            ->where('txtOrderID', $orderId)
            ->update([
                'txtStatus' => 'PAID',
                'dtmUpdatedDate' => date('Y-m-d H:i:s'),
                'txtUpdatedBy' => 'SYSTEM_CALLBACK'
            ]);

        // You can add more logic here like:
        // - Send confirmation email
        // - Update inventory
        // - Create invoice
        // - etc.
    }
}
