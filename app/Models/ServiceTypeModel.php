<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceTypeModel extends Model
{
    protected $table = 'm_service_types'; 
    protected $primaryKey = 'intServiceTypeID';    protected $allowedFields = [
        'txtGUID', 'txtName', 'txtSlug', 'txtDescription', 'txtIcon', 'txtCategory',
        'bitIsSystem', 'bitIsApproved', 'intRequestedBy', 'intApprovedBy',
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
        $data['data']['bitActive'] = $data['data']['bitActive'] ?? 1;
        return $data;
    }

    public function getApprovedTypes()
    {
        return $this->where('bitIsApproved', 1)
                   ->where('bitActive', 1)
                   ->findAll();
    }

    public function getPendingApprovals()
    {
        return $this->where('bitIsApproved', 0)
                   ->where('bitActive', 1)
                   ->findAll();
    }

    public function getWithRequestorDetails()
    {
        return $this->db->table($this->table . ' st')
            ->select('st.*, u.txtFullName as requested_by_name')
            ->join('m_user u', 'st.intRequestedBy = u.intUserID', 'left')
            ->get()
            ->getResultArray();
    }

    public function getByCategory($category)
    {
        return $this->where('txtCategory', $category)
                    ->where('bitIsApproved', 1)
                    ->where('bitActive', 1)
                    ->findAll();
    }

    public function getBySlug($slug)
    {
        return $this->where('txtSlug', $slug)
                    ->where('bitIsApproved', 1)
                    ->where('bitActive', 1)
                    ->first();
    }

    public function getAllCategories()
    {
        return $this->db->table($this->table)
                        ->select('DISTINCT txtCategory')
                        ->where('bitIsApproved', 1)
                        ->where('bitActive', 1)
                        ->get()
                        ->getResultArray();
    }
}
