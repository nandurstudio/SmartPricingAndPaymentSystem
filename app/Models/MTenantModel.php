<?php

namespace App\Models;

use CodeIgniter\Model;

class MTenantModel extends Model
{
    protected $table = 'm_tenants';
    protected $primaryKey = 'intTenantID';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'txtGUID',
        'txtTenantName',
        'txtSlug',
        'txtDomain',
        'txtTenantCode',
        'intServiceTypeID',
        'intOwnerID',
        'txtSubscriptionPlan',
        'txtSubscriptionStatus',
        'dtmSubscriptionStartDate',
        'dtmSubscriptionEndDate',
        'dtmTrialEndsAt',
        'jsonSettings',
        'jsonPaymentSettings',
        'txtMidtransClientKey',
        'txtMidtransServerKey',
        'txtLogo',
        'txtTheme',
        'txtStatus',
        'bitActive',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'dtmCreatedDate';
    protected $updatedField = 'dtmUpdatedDate';
    
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'txtTenantName' => 'required|min_length[3]|max_length[100]',
        'intServiceTypeID' => 'required|numeric',
        'intOwnerID' => 'required|numeric'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get tenant with service type details
     */
    public function getWithServiceType(int $id = null)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('t.*, st.txtName as service_type_name, u.txtFullName as owner_name, u.txtEmail as owner_email')
            ->join('m_service_types st', 't.intServiceTypeID = st.intServiceTypeID', 'left')
            ->join('m_user u', 't.intOwnerID = u.intUserID', 'left');

        if ($id) {
            return $builder->where('t.intTenantID', $id)->get()->getRowArray();
        }

        return $builder->orderBy('t.dtmCreatedDate', 'DESC')->get()->getResultArray();
    }

    /**
     * Get tenant by slug
     */
    public function getBySlug(string $slug)
    {
        return $this->where('txtSlug', $slug)->first();
    }

    /**
     * Get tenant with owner details
     */
    public function getWithOwner(int $id = null)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('t.*, u.txtFullName as owner_name, u.txtEmail as owner_email')
            ->join('m_user u', 't.intOwnerID = u.intUserID', 'left');

        if ($id) {
            return $builder->where('t.intTenantID', $id)->get()->getRowArray();
        }

        return $builder->orderBy('t.dtmCreatedDate', 'DESC')->get()->getResultArray();
    }

    /**
     * Get tenants by service type
     */
    public function getByServiceType(int $serviceTypeId)
    {
        return $this->where('intServiceTypeID', $serviceTypeId)
                    ->where('bitActive', 1)
                    ->findAll();
    }

    /**
     * Search tenants
     */
    public function search(string $keyword)
    {
        return $this->like('txtTenantName', $keyword)
                    ->orLike('txtSlug', $keyword)
                    ->orLike('txtDomain', $keyword)
                    ->where('bitActive', 1)
                    ->findAll();
    }

    /**
     * Get popular service types
     */
    public function getPopularServiceTypes($limit = 5)
    {
        return $this->db->table($this->table . ' t')
                        ->select('st.txtName, st.txtIcon, COUNT(t.intTenantID) as tenant_count')
                        ->join('m_service_types st', 't.intServiceTypeID = st.intServiceTypeID')
                        ->where('t.bitActive', 1)
                        ->groupBy('st.intServiceTypeID, st.txtName, st.txtIcon')
                        ->orderBy('tenant_count', 'DESC')
                        ->limit($limit)
                        ->get()
                        ->getResultArray();
    }

    /**
     * Activate subscription for tenant
     */
    public function activateSubscription($tenantId, $transactionData)
    {
        $plan = $this->find($tenantId)['txtSubscriptionPlan'];
        $duration = $this->getSubscriptionDuration($plan);
        
        $data = [
            'txtSubscriptionStatus' => 'active',
            'txtStatus' => 'active',
            'dtmSubscriptionStartDate' => date('Y-m-d H:i:s'),
            'dtmSubscriptionEndDate' => date('Y-m-d H:i:s', strtotime("+{$duration} months")),
            'jsonPaymentSettings' => json_encode([
                'currency' => 'IDR',
                'last_payment_id' => $transactionData['transaction_id'] ?? null,
                'last_payment_status' => $transactionData['transaction_status'] ?? null,
                'last_payment_date' => date('Y-m-d H:i:s')
            ])
        ];

        return $this->update($tenantId, $data);
    }

    /**
     * Update Midtrans keys
     */
    public function updateMidtransKeys($tenantId, $clientKey, $serverKey)
    {
        return $this->update($tenantId, [
            'txtMidtransClientKey' => $clientKey,
            'txtMidtransServerKey' => $serverKey
        ]);
    }

    /**
     * Generate tenant slug
     */
    public function generateTenantSlug($name)
    {
        $baseSlug = url_title($name, '-', true);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->where('txtSlug', $slug)->first() !== null) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get user tenants
     */
    public function getUserTenants($userId, $roleId)
    {
        if ($roleId == 1) { // Admin sees all tenants
            return $this->findAll();
        }
        
        // Regular users see only their own tenants
        return $this->where('intOwnerID', $userId)
                   ->where('bitActive', 1)
                   ->findAll();
    }

    /**
     * Get subscription duration
     */
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