<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'mRole';
    protected $primaryKey = 'intRoleID';

    protected $allowedFields = [
        'txtRoleName',
        'txtRoleDesc',
        'txtRoleNote',
        'bitActive',
        'txtInsertedBy',
        'txtUpdatedBy',
        'txtGUID'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'dtmInsertedDate';
    protected $updatedField  = 'dtmUpdatedDate';

    protected $beforeInsert = ['generateGUID', 'setInsertedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    protected function generateGUID(array $data)
    {
        $data['data']['txtGUID'] = uniqid();
        return $data;
    }

    protected function setInsertedBy(array $data)
    {
        $data['data']['txtInsertedBy'] = session()->get('userID');
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        $data['data']['txtUpdatedBy'] = session()->get('userID');
        return $data;
    }

    public function getMenuByRole($roleID)
    {
        return $this->db->table('mMenu')
            ->select('mMenu.*')
            ->join('trRoleMenuAccess', 'mMenu.intMenuID = trRoleMenuAccess.intMenuID')
            ->where('trRoleMenuAccess.intRoleID', $roleID)
            ->where('trRoleMenuAccess.bitActive', 1)
            ->orderBy('mMenu.intSortOrder', 'ASC')
            ->get()
            ->getResult();
    }
}
