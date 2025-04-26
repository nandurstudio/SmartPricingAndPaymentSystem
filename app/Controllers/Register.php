<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;

class Register extends BaseController
{
    public function index()
    {
        // Ambil data role untuk ditampilkan di dropdown
        $roleModel = new RoleModel();
        $roles = $roleModel->findAll();

        // Mengembalikan view pendaftaran
        return view('register', [
            'roles' => $roles,
            'validation' => \Config\Services::validation()
        ]);
    }

    public function createUser()
    {
        // Aturan validasi input
        $rules = [
            'txtFullName' => 'required',
            'txtNick' => 'required|alpha_numeric|min_length[3]|max_length[3]|strtoupper',
            'txtEmpID' => 'required|is_unique[mUser.txtEmpID]',
            'txtUserName' => 'required',
            'txtEmail' => 'required|valid_email',
            'txtPassword' => 'required|min_length[8]',
            'intRoleID' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Persiapan data untuk insert
        $data = [
            'txtUserName' => $this->request->getPost('txtUserName'),
            'txtFullName' => $this->request->getPost('txtFullName'),
            'txtNick' => strtoupper($this->request->getPost('txtNick')),
            'txtEmpID' => $this->request->getPost('txtEmpID'),
            'intDepartmentID' => $this->request->getPost(['intDepartmentID']),
            'txtEmail' => $this->request->getPost('txtEmail'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtPassword' => \App\Helpers\Encrypt::encryptPassword($this->request->getPost('txtPassword')),
            'intRoleID' => $this->request->getPost('intRoleID'),
            'dtmLastLogin' => null,
            'txtInsertedBy' => 'system',
            'dtmInsertedDate' => date('Y-m-d H:i:s'),
            'txtUpdatedBy' => 'system',
            'dtmUpdatedDate' => date('Y-m-d H:i:s'),
            'txtGUID' => bin2hex(random_bytes(16))
        ];

        $model = new UserModel();

        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
        }

        return redirect()->to('/users')->with('success', 'User created successfully');
    }
}
