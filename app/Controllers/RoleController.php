<?php

namespace App\Controllers;

use App\Models\RoleModel;
use CodeIgniter\Controller;

class RoleController extends Controller
{
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data['roles'] = $this->roleModel->findAll();
        return view('role/index', $data);
    }

    public function create()
    {
        return view('role/create_role');
    }

    public function store()
    {
        $this->roleModel->save([
            'txtRoleName' => $this->request->getVar('txtRoleName'),
            'txtRoleDesc' => $this->request->getVar('txtRoleDesc'),
            'txtRoleNote' => $this->request->getVar('txtRoleNote'),
            'bitActive'   => $this->request->getVar('bitActive') ? 1 : 0,
        ]);

        return redirect()->to('/role')->with('success', 'Role successfully created.');
    }

    public function edit($id)
    {
        $data['role'] = $this->roleModel->find($id);
        return view('role/edit', $data);
    }

    public function update($id)
    {
        $this->roleModel->update($id, [
            'txtRoleName' => $this->request->getVar('txtRoleName'),
            'txtRoleDesc' => $this->request->getVar('txtRoleDesc'),
            'txtRoleNote' => $this->request->getVar('txtRoleNote'),
            'bitActive'   => $this->request->getVar('bitActive') ? 1 : 0,
        ]);

        return redirect()->to('/role')->with('success', 'Role successfully updated.');
    }

    public function delete($id)
    {
        $this->roleModel->delete($id);
        return redirect()->to('/role')->with('success', 'Role successfully deleted.');
    }

    public function view($id)
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Role not found');
        }
        return view('role/view', ['role' => $role]); // Pastikan nama file sesuai
    }
}
