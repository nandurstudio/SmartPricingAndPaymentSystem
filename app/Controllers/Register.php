<?php

namespace App\Controllers;

use App\Models\MUserModel;
use App\Models\MRoleModel;

class Register extends BaseController
{
    public function index()
    {
        // Ambil data role untuk ditampilkan di dropdown
        $roleModel = new MRoleModel();
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
            'txtEmail' => $this->request->getPost('txtEmail'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0, // 1 untuk aktif, 0 untuk tidak aktif
            'txtPassword' => \App\Helpers\Encrypt::encryptPassword($this->request->getPost('txtPassword')),
            'intRoleID' => $this->request->getPost('intRoleID'),
            'dtmLastLogin' => null, // Login pertama kali
            'txtCreatedBy' => 'system', // Pengguna yang membuat akun
            'dtmCreatedDate' => date('Y-m-d H:i:s'), // Waktu pembuatan akun
            'txtUpdatedBy' => 'system', // Pengguna yang terakhir mengupdate
            'dtmUpdatedDate' => date('Y-m-d H:i:s'), // Waktu terakhir update
            'txtGUID' => bin2hex(random_bytes(16)), // UUID
            'reset_token' => null, // Token reset password (jika ada)
            'token_created_at' => null, // Waktu pembuatan token reset (jika ada)
            'txtPhoto' => null, // Foto pengguna (jika ada)
            'dtmJoinDate' => date('Y-m-d H:i:s'), // Tanggal bergabung
            'bitOnlineStatus' => 0, // Status online, 0 jika offline
            'google_auth_token' => null // Token Google Auth (jika ada)
        ];

        $model = new MUserModel();

        // Menyisipkan data ke dalam database
        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
        }

        return redirect()->to('/users')->with('success', 'User created successfully');
    }
}
