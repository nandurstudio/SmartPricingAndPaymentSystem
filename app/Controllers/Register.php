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
        $validation = \Config\Services::validation();        // Define validation rules including password confirmation
        $rules = [
            'txtFullName'      => 'required|min_length[3]|max_length[100]',
            'txtUserName'      => 'required|min_length[4]|max_length[50]|is_unique[m_user.txtUserName]',
            'txtEmail'         => 'required|valid_email|max_length[100]|is_unique[m_user.txtEmail]',
            'txtPassword'      => 'required|min_length[6]|max_length[255]',
            'password_confirm' => 'required|matches[txtPassword]', // Ensures this matches txtPassword
            'terms_agreement'  => 'required', // Terms and conditions must be accepted
        ];

        if (!$this->validate($rules)) {
            // If validation fails, return errors to the view
            // The view's JavaScript will handle displaying these errors
            return $this->response->setJSON(['success' => false, 'errors' => $validation->getErrors()]);
        }        // If validation passes, proceed to create user
        $data = [
            'txtFullName'  => $this->request->getPost('txtFullName'),
            'txtUserName'  => $this->request->getPost('txtUserName'),
            'txtEmail'     => $this->request->getPost('txtEmail'),
            'txtPassword'  => $this->userModel->hashPassword($this->request->getPost('txtPassword')),
            'intRoleID'    => 5, // Default role: Customer
            'bitActive'    => 1, // Always active for new registrations
            'txtCreatedBy' => 'register_form', // Or any identifier for the registration source
            'txtGUID'      => uniqid(), // Generate a unique ID
            'txtPhoto'     => 'default.png', // Default photo, or handle photo upload separately
            'dtmJoinDate'  => date('Y-m-d H:i:s'), // Current timestamp
            'dtmCreatedDate' => date('Y-m-d H:i:s'), // Current timestamp for creation
        ];

        if ($this->userModel->insert($data)) {
            // Registration successful
            // The view's JavaScript expects a redirect URL
            return $this->response->setJSON(['success' => true, 'redirect' => base_url('/login'), 'message' => 'Registration successful! Please login.']);
        } else {
            // Database insertion failed
            return $this->response->setJSON(['success' => false, 'message' => 'Registration failed. Please try again.']);
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