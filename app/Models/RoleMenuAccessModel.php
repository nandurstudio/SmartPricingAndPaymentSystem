<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleMenuAccessModel extends Model
{
    protected $table = 'trRoleMenuAccess';
    protected $primaryKey = 'intRoleMenuAccessID';
    protected $allowedFields = [
        'intRoleID',
        'intMenuID',
        'bitCanView',
        'bitCanAdd',
        'bitCanEdit',
        'bitCanDelete',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID',
        'bitActive'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'dtmInsertedDate';
    protected $updatedField = 'dtmUpdatedDate';

    public function getRoleMenuAccess($id = null)
    {
        if ($id) {
            return $this->where(['intRoleMenuAccessID' => $id])->first();
        }
        return $this->findAll();
    }

    public function getAccessByRole($roleID)
    {
        return $this->where('intRoleID', $roleID)->findAll();
    }

    public function saveAccess(array $data)
    {
        $data['txtInsertedBy'] = session()->get('username') ?? 'system';
        return $this->insert($data);
    }

    public function updateAccess($id, array $data)
    {
        $data['txtUpdatedBy'] = session()->get('username') ?? 'system';
        return $this->update($id, $data);
    }

    public function index()
    {
        $data['menus'] = $this->getMenu(); // Mengambil menu dinamis
        // Data lainnya yang perlu dikirim ke view
        return view('role_menu_access/index', $data);
    }
}
