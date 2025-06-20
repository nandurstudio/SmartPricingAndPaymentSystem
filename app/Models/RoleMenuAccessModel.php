<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleMenuAccessModel extends Model
{
    protected $table = 'm_role_menu';
    protected $primaryKey = 'intRoleMenuAccessID';    protected $allowedFields = [
        'intRoleID',
        'intMenuID',
        'bitActive'
    ];    protected $returnType = 'array';
    protected $useTimestamps = false;

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
