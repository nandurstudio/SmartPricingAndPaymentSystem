<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceTypeModel extends Model
{
    protected $table = 'm_service_types'; 
    protected $primaryKey = 'intServiceTypeID';
    
    protected $allowedFields = [
        'txtGUID', 'txtName', 'txtSlug', 'txtDescription', 'txtIcon', 'txtCategory',
        'bitIsSystem', 'bitIsApproved', 'intRequestedByID', 'intApprovedByID',
        'dtmApprovedDate', 'jsonDefaultAttributes', 'bitActive',
        'dtmCreatedDate', 'txtCreatedBy', 'dtmUpdatedDate', 'txtUpdatedBy'
    ];

    protected $useTimestamps = false;
    protected $beforeInsert = ['addGuid'];    protected function addGuid(array $data)
    {
        if (!isset($data['data']['txtGUID'])) {
            $data['data']['txtGUID'] = uniqid('st_', true);
        }
        $data['data']['dtmCreatedDate'] = date('Y-m-d H:i:s');
        $data['data']['bitActive'] = $data['data']['bitActive'] ?? true;
        return $data;
    }

    public function getApprovedTypes()
    {
        return $this->where('is_approved', true)
                   ->where('is_active', true)
                   ->findAll();
    }

    public function getPendingApprovals()
    {
        return $this->where('is_approved', false)
                   ->where('is_active', true)
                   ->findAll();
    }

    public function getWithRequestorDetails()
    {
        return $this->db->table($this->table . ' st')
            ->select('st.*, u.txtFullName as requested_by_name')
            ->join('m_user u', 'st.requested_by = u.intUserID', 'left')
            ->get()
            ->getResultArray();
    }

    public function getByCategory($category)
    {
        return $this->where('category', $category)
                    ->where('is_approved', true)
                    ->where('is_active', true)
                    ->findAll();
    }

    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)
                    ->where('is_approved', true)
                    ->where('is_active', true)
                    ->first();
    }

    public function getAllCategories()
    {
        return $this->db->table($this->table)
                        ->select('DISTINCT category')
                        ->where('is_approved', true)
                        ->where('is_active', true)
                        ->get()
                        ->getResultArray();
    }
}
