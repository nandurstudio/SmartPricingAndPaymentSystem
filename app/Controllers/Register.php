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

        // Menambahkan pengecekan is_unique untuk username
        $rules = [
            'txtUserName' => 'required|min_length[4]|max_length[50]|is_unique[m_user.txtUserName]',  // Tambah pengecekan is_unique
            'txtFullName' => 'required|min_length[3]|max_length[100]',
            'txtEmail'    => 'required|valid_email|max_length[100]|is_unique[m_user.txtEmail]',  // Pengecekan untuk email sudah ada
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

    // Pengecekan untuk username
    public function checkUsername()
    {
        $username = $this->request->getPost('username');
        $user = $this->userModel->where('txtUserName', $username)->first();

        if ($user) {
            return $this->response->setBody('exists');
        } else {
            return $this->response->setBody('not_exists');
        }
    }

    // Pengecekan untuk email
    public function checkEmail()
    {
        $email = $this->request->getPost('email');
        $user = $this->userModel->where('txtEmail', $email)->first();

        if ($user) {
            return $this->response->setBody('exists');
        } else {
            return $this->response->setBody('not_exists');
        }
    }
}