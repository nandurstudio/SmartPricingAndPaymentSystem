<?php

namespace App\Controllers;

use App\Models\MUserModel;
use App\Models\MRoleModel;
use App\Models\MenuModel;
use App\Helpers\Encrypt;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $userModel;
    protected $menuModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new MUserModel();
        $this->menuModel = new MenuModel();
        $this->roleModel = new MRoleModel();
    }

    // Index - Default action, shows user list
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            helper('flashmessage');
            set_flash_message('error', 'You must be logged in to access this page.');
            return redirect()->to('/login');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);

        // Get users with their roles
        $users = $this->userModel->getUsersWithRole();
        return view('users/index', [
            'users' => $users,
            'menus' => $menus,
            'pageTitle' => 'User Management',
            'cardTitle' => 'User List',
            'icon' => 'bi bi-people' // Updated to use Bootstrap Icons
        ]);
    }

    // Edit user form
    public function edit($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            helper('flashmessage');
            set_flash_message('error', 'You must be logged in to access this page.');
            return redirect()->to('/login');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        $roles = $this->roleModel->findAll();
        $menus = $this->menuModel->getMenusByRole(session()->get('roleID'));
        return view('users/edit', [
            'user' => $user,
            'roles' => $roles,
            'menus' => $menus,
            'pageTitle' => 'Edit User',
            'cardTitle' => 'Edit User Profile',
            'icon' => 'bi bi-pencil' // Updated to use Bootstrap Icons
        ]);
    }

    // Update user
    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        // Validation rules
        $rules = [
            'txtUserName' => [
                'label' => 'Username',
                'rules' => "required|min_length[3]|max_length[50]|is_unique[m_user.txtUserName,intUserID,{$id}]"
            ],
            'txtFullName' => [
                'label' => 'Full Name',
                'rules' => 'required|min_length[3]|max_length[100]'
            ],
            'txtEmail' => [
                'label' => 'Email',
                'rules' => "required|valid_email|is_unique[m_user.txtEmail,intUserID,{$id}]"
            ],
            'intRoleID' => [
                'label' => 'Role',
                'rules' => 'required|numeric'
            ],
            'txtPhoto' => [
                'label' => 'Profile Picture',
                'rules' => 'permit_empty|is_image[txtPhoto]|max_size[txtPhoto,2048]'
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'txtUserName' => $this->request->getPost('txtUserName'),
            'txtFullName' => $this->request->getPost('txtFullName'),
            'txtEmail' => $this->request->getPost('txtEmail'),
            'intRoleID' => $this->request->getPost('intRoleID'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s')
        ];

        // Handle password update if provided
        if ($password = $this->request->getPost('txtPassword')) {
            $updateData['txtPassword'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Handle photo upload if provided
        if ($photo = $this->request->getFile('txtPhoto')) {
            if ($photo->isValid() && !$photo->hasMoved()) {
                $newName = $photo->getRandomName();
                $photo->move(FCPATH . 'uploads/photos', $newName); // Ubah ke FCPATH
                $updateData['txtPhoto'] = $newName;

                // Delete old photo if exists
                if ($user['txtPhoto'] && $user['txtPhoto'] !== 'default.png' && file_exists(FCPATH . 'uploads/photos/' . $user['txtPhoto'])) {
                    unlink(FCPATH . 'uploads/photos/' . $user['txtPhoto']);
                }
            }
        }

        try {
            $this->userModel->update($id, $updateData);
            return redirect()->to('/users')
                ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user');
        }
    }

    // Toggle user active status
    public function toggleStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        try {
            $this->userModel->update($id, [
                'bitActive' => !$user['bitActive'],
                'txtUpdatedBy' => session()->get('userName'),
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User status updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to update user status'
            ]);
        }
    }

    // View user details
    public function view($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            helper('flashmessage');
            set_flash_message('error', 'You must be logged in to access this page.');
            return redirect()->to('/login');
        }

        if ($id === null) {
            return redirect()->to('/users')->with('error', 'User ID is required');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);

        $user = $this->userModel->getUserWithRole($id);

        if (empty($user)) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        return view('users/view', [
            'pageTitle' => 'User Details',
            'cardTitle' => 'View User Information',
            'icon' => 'bi bi-person',
            'user' => $user,
            'menus' => $menus
        ]);
    }
}
