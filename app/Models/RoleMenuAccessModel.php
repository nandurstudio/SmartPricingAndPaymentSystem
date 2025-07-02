<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleMenuAccessModel extends Model
{
    protected $table = 'm_role_menu';
    protected $primaryKey = 'intRoleMenuID';    // Sesuaikan dengan nama field di database
    protected $allowedFields = [
        'txtGUID',
        'intRoleID',
        'intMenuID',
        'bitActive',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function getAllRoleMenuAccess()
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table.'.*, m_role.txtRoleName, m_menu.txtMenuName');
        $builder->join('m_role', 'm_role.intRoleID = '.$this->table.'.intRoleID', 'left');
        $builder->join('m_menu', 'm_menu.intMenuID = '.$this->table.'.intMenuID', 'left');
        $builder->orderBy('m_role.txtRoleName', 'ASC');
        $builder->orderBy('m_menu.txtMenuName', 'ASC');
        
        $result = $builder->get()->getResultArray();
        
        // Debug log
        log_message('debug', 'Role Menu Access Query: ' . $this->db->getLastQuery());
        log_message('debug', 'Role Menu Access Data: ' . print_r($result, true));
        
        return $result;
    }

    public function getRoleMenuAccess($id = null)
    {
        if ($id) {
            $result = $this->where($this->primaryKey, $id)->first();
            log_message('debug', 'Get Single Role Menu Access: ' . print_r($result, true));
            return $result;
        }
        return $this->findAll();
    }

    public function getAccessByRole($roleID)
    {
        return $this->where('intRoleID', $roleID)
                    ->orderBy('intMenuID', 'ASC')
                    ->findAll();
    }

    public function saveAccess(array $data)
    {
        $data['txtCreatedBy'] = session()->get('userName') ?? 'system';
        $data['dtmCreatedDate'] = date('Y-m-d H:i:s');
        
        log_message('debug', 'Saving Role Menu Access: ' . print_r($data, true));
        return $this->insert($data);
    }

    public function updateAccess($id, array $data)
    {
        $data['txtUpdatedBy'] = session()->get('userName') ?? 'system';
        $data['dtmUpdatedDate'] = date('Y-m-d H:i:s');
        
        log_message('debug', 'Updating Role Menu Access ID ' . $id . ': ' . print_r($data, true));
        return $this->update($id, $data);
    }
}
