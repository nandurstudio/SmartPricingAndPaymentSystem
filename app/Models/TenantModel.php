<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $table = 'm_tenant'; // Nama tabel yang sesuai dengan database
    protected $primaryKey = 'id';
      protected $allowedFields = [
        'guid',
        'name', 
        'slug',
        'domain',
        'tenant_code',
        'service_type_id',
        'owner_id',
        'subscription_plan',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'trial_ends_at',
        'settings',
        'payment_settings',
        'midtrans_client_key',
        'midtrans_server_key',
        'logo',
        'theme',
        'status',
        'is_active',
        'created_date',
        'created_by',
        'updated_date',
        'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_date';
    protected $updatedField = 'updated_date';

    public function getWithServiceType(int $id = null)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('t.*, st.name as service_type_name, u.txtFullName as owner_name, u.txtEmail as owner_email')
            ->join('m_service_types st', 't.service_type_id = st.id', 'left') // Updated from 'service_types' to 'm_service_types'
            ->join('m_user u', 't.owner_id = u.intUserID', 'left');

        if ($id) {
            return $builder->where('t.id', $id)->get()->getRowArray();
        }

        return $builder->orderBy('t.created_date', 'DESC')->get()->getResultArray();
    }

    // Get tenant by slug
    public function getBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    // Get tenant with owner details
    public function getWithOwner(int $id = null)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('t.*, u.txtFullName as owner_name, u.txtEmail as owner_email, u.phone as owner_phone')
            ->join('m_user u', 't.owner_id = u.intUserID', 'left');

        if ($id) {
            return $builder->where('t.id', $id)->get()->getRowArray();
        }

        return $builder->orderBy('t.created_date', 'DESC')->get()->getResultArray();
    }

    // Get tenants by service type
    public function getByServiceType(int $serviceTypeId)
    {
        return $this->where('service_type_id', $serviceTypeId)
                    ->where('is_active', true)
                    ->findAll();
    }

    // Search tenants
    public function search(string $keyword)
    {
        return $this->like('name', $keyword)
                    ->orLike('slug', $keyword)
                    ->orLike('domain', $keyword)
                    ->where('is_active', true)
                    ->findAll();
    }

    // Get popular service types
    public function getPopularServiceTypes($limit = 5)
    {
        return $this->db->table($this->table . ' t')
                        ->select('st.name, st.icon, COUNT(t.id) as tenant_count')
                        ->join('m_service_types st', 't.service_type_id = st.id') // Updated from 'service_types' to 'm_service_types'
                        ->where('t.is_active', true)
                        ->groupBy('st.id, st.name, st.icon')
                        ->orderBy('tenant_count', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->getResultArray();
    }

    public function activateSubscription($tenantId, $transactionData)
    {
        $plan = $this->find($tenantId)['subscription_plan'];
        $duration = $this->getSubscriptionDuration($plan);
        
        $data = [
            'subscription_status' => 'active',
            'status' => 'active',
            'subscription_start_date' => date('Y-m-d H:i:s'),
            'subscription_end_date' => date('Y-m-d H:i:s', strtotime("+{$duration} months")),
            'payment_settings' => json_encode([
                'currency' => 'IDR',
                'last_payment_id' => $transactionData['transaction_id'] ?? null,
                'last_payment_status' => $transactionData['transaction_status'] ?? null,
                'last_payment_date' => date('Y-m-d H:i:s')
            ])
        ];

        return $this->update($tenantId, $data);
    }

    public function updateMidtransKeys($tenantId, $clientKey, $serverKey)
    {
        return $this->update($tenantId, [
            'midtrans_client_key' => $clientKey,
            'midtrans_server_key' => $serverKey
        ]);
    }

    private function getSubscriptionDuration($plan)
    {
        $durations = [
            'free' => 1,
            'basic' => 1,
            'premium' => 3,
            'enterprise' => 12
        ];

        return $durations[$plan] ?? 1;
    }
}
