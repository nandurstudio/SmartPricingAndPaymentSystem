<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $table = 'm_tenants'; // Updated from 'tenants' to 'm_tenants'
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'guid', 'name', 'slug', 'domain', 'service_type_id', 'owner_id',
        'subscription_plan', 'status', 'settings', 'payment_settings',
        'is_active', 'created_date', 'created_by', 'updated_date', 'updated_by'
    ];

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
}
