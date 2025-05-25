<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'm_category'; // Using correct table name
    protected $primaryKey = 'intCategoryID';
    protected $allowedFields = [
        'txtCategoryName', 'txtDesc', 'icon', 'tenant_id', 'service_type_id',
        'bitActive', 'dtmCreatedDate', 'txtCreatedBy', 'dtmLastUpdatedDate', 'txtLastUpdatedBy'
    ];

    protected $useTimestamps = false;
    
    // Before getting any data, check if the required columns exist and handle accordingly
    protected function initialize()
    {
        parent::initialize();
        
        $this->ensureColumnsExist();
    }
    
    private function ensureColumnsExist()
    {
        // Only run this check in development mode
        if (ENVIRONMENT !== 'development') {
            return;
        }
        
        $db = \Config\Database::connect();
        
        // Check if m_category table exists
        if (!$db->tableExists($this->table)) {
            return;
        }
        
        // Get current field names
        $fields = $db->getFieldNames($this->table);
        
        // Initialize forge if any columns are missing
        if (!in_array('txtDesc', $fields) || 
            !in_array('icon', $fields) || 
            !in_array('tenant_id', $fields) || 
            !in_array('service_type_id', $fields)) {
            $forge = \Config\Database::forge();
            
            // Add missing fields
            if (!in_array('txtDesc', $fields)) {
                try {
                    $forge->addColumn($this->table, [
                        'txtDesc' => [
                            'type' => 'TEXT',
                            'null' => true,
                            'after' => 'txtCategoryName',
                        ]
                    ]);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to add txtDesc column: ' . $e->getMessage());
                }
            }
            
            // Continue with other fields if needed
            // ...
        }
    }

    public function getCategoriesWithTenantInfo()
    {
        return $this->db->table($this->table . ' c')
            ->select('c.*, t.name as tenant_name, st.name as service_type_name')
            ->join('m_tenants t', 'c.tenant_id = t.id', 'left') // Updated join table
            ->join('m_service_types st', 'c.service_type_id = st.id', 'left') // Updated join table
            ->where('c.bitActive', 1)
            ->orderBy('c.dtmCreatedDate', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getCategoriesByTenant($tenantId)
    {
        $builder = $this->db->table($this->table . ' c')
            ->select('c.*, t.name as tenant_name, st.name as service_type_name')
            ->join('m_tenants t', 'c.tenant_id = t.id', 'left') // Updated join table
            ->join('m_service_types st', 'c.service_type_id = st.id', 'left') // Updated join table
            ->where('c.bitActive', 1);

        if ($tenantId > 0) {
            $builder->where('c.tenant_id', $tenantId);
        } else {
            $builder->where('c.tenant_id IS NULL');
        }

        return $builder->orderBy('c.dtmCreatedDate', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    public function getCategoryWithDetails($id)
    {
        return $this->db->table($this->table . ' c')
            ->select('c.*, t.name as tenant_name, st.name as service_type_name, st.icon as service_type_icon')
            ->join('m_tenants t', 'c.tenant_id = t.id', 'left') // Updated join table
            ->join('m_service_types st', 'c.service_type_id = st.id', 'left') // Updated join table
            ->where('c.intCategoryID', $id)
            ->get()
            ->getRowArray();
    }
}
