<?php

namespace App\Controllers;

use App\Models\MUserModel;

class Register extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new MUserModel();
    }

    public function index()
    {
        return view('register'); // Tampilkan halaman register
    }

    public function createUser()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'txtUserName' => 'required|min_length[4]|max_length[50]',
            'txtFullName' => 'required|min_length[3]|max_length[100]',
            'txtEmail'    => 'required|valid_email|max_length[100]|is_unique[m_user.txtEmail]',
            'txtPassword' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'txtUserName' => $this->request->getPost('txtUserName'),
            'txtFullName' => $this->request->getPost('txtFullName'),
            'txtEmail'    => $this->request->getPost('txtEmail'),
            'txtPassword' => $this->userModel->hashPassword($this->request->getPost('txtPassword')),
            'intRoleID'   => $this->request->getPost('intRoleID') ?? 5,
            'bitActive'   => $this->request->getPost('bitActive') ? 1 : 0,
            'txtCreatedBy' => 'register_form',
            'txtGUID'     => uniqid(),
            'txtPhoto'    => 'default.png', // Set default photo
            'dtmJoinDate' => date('Y-m-d H:i:s'),
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Registrasi gagal. Silakan coba lagi.');
        }
    }
}