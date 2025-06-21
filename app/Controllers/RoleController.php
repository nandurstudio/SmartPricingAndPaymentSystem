<?php

namespace App\Controllers;

use App\Models\MRoleModel;
// use App\Models\RoleModel; // Remove or comment out this line if RoleModel does not exist
use CodeIgniter\Controller;
use App\Models\MRoleModel as RoleModel; // Alias MRoleModel as RoleModel for usage below

class RoleController extends Controller
{
    protected $roleModel;
    protected $menuModel;

    public function __construct()
    {
        $this->roleModel = new MRoleModel();
        $this->menuModel = new \App\Models\MenuModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);

        // Get roles and ensure default values for nullable fields
        $roles = array_map(function($role) {
            return array_merge($role, [
                'bitActive' => $role['bitActive'] ?? 1,
                'txtRoleDesc' => $role['txtRoleDesc'] ?? '',
                'txtCreatedBy' => $role['txtCreatedBy'] ?? 'system',
                'dtmCreatedDate' => $role['dtmCreatedDate'] ?? date('Y-m-d H:i:s')
            ]);
        }, $this->roleModel->findAll());

        return view('role/index', [
            'roles' => $roles,
            'menus' => $menus,
            'title' => 'Role Management',
            'pageTitle' => 'Role Management',
            'pageSubTitle' => 'Manage system roles and permissions',
            'icon' => 'shield'
        ]);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);

        return view('role/create', [
            'menus' => $menus,
            'title' => 'Create Role',
            'pageTitle' => 'Create New Role',
            'pageSubTitle' => 'Add a new role to the system',
            'icon' => 'shield-plus'
        ]);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('message', 'You must be logged in to access this page.')
                ->with('message_type', 'danger');
        }
        
        // Validation rules
        $validation = \Config\Services::validation();
        $rules = [
            'txtRoleName' => 'required|min_length[3]|max_length[50]',
            'txtRoleDesc' => 'required|min_length[3]|max_length[255]',
            'txtRoleNote' => 'permit_empty|max_length[500]',
            'bitActive'   => 'permit_empty'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $currentTime = date('Y-m-d H:i:s');
        if ($this->roleModel->save([
            'txtRoleName' => $this->request->getPost('txtRoleName'),
            'txtRoleDesc' => $this->request->getPost('txtRoleDesc'),
            'txtRoleNote' => $this->request->getPost('txtRoleNote'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtCreatedBy' => session()->get('userName') ?? 'system',
            'dtmCreatedDate' => $currentTime,
            'txtUpdatedBy' => session()->get('userName') ?? 'system',
            'dtmUpdatedDate' => $currentTime,
            'txtGUID' => uniqid('role_', true),
        ])) {
            session()->setFlashdata('success', 'Role created successfully');
            return redirect()->to('/roles');
        } else {
            session()->setFlashdata('error', 'Failed to create role');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);
        $role = $this->roleModel->find($id);

        if (!$role) {
            return redirect()->to('/roles')->with('message', 'Role not found')->with('message_type', 'danger');
        }

        // Set default values for nullable fields
        $role['bitActive'] = $role['bitActive'] ?? 1;
        $role['txtRoleDesc'] = $role['txtRoleDesc'] ?? '';
        $role['txtRoleNote'] = $role['txtRoleNote'] ?? '';

        return view('role/edit', [
            'role' => $role,
            'menus' => $menus,
            'title' => 'Edit Role',
            'pageTitle' => 'Edit Role',
            'pageSubTitle' => 'Modify role information',
            'icon' => 'pencil'
        ]);
    }    public function update($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('message', 'You must be logged in to access this page.')
                ->with('message_type', 'danger');
        }

        if (!$id) {
            return redirect()->to('/roles')
                ->with('message', 'No ID provided')
                ->with('message_type', 'danger');
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return redirect()->to('/roles')
                ->with('message', 'Role not found')
                ->with('message_type', 'danger');
        }

        $validation = \Config\Services::validation();
        $rules = [
            'txtRoleName' => 'required|min_length[3]|max_length[50]',
            'txtRoleDesc' => 'required|min_length[3]|max_length[255]',
            'txtRoleNote' => 'permit_empty|max_length[500]',
            'bitActive'   => 'permit_empty'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $currentTime = date('Y-m-d H:i:s');
        $data = [
            'txtRoleName' => $this->request->getPost('txtRoleName'),
            'txtRoleDesc' => $this->request->getPost('txtRoleDesc'),
            'txtRoleNote' => $this->request->getPost('txtRoleNote'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => $currentTime
        ];

        if ($this->roleModel->update($id, $data)) {
            session()->setFlashdata('success', 'Role updated successfully');
            return redirect()->to('/roles');
        } else {
            session()->setFlashdata('error', 'Failed to update role');
            return redirect()->back()->withInput();
        }
    }

    // Method baru untuk AJAX update
    public function updateAjax($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'You must be logged in to perform this action.']);
        }

        if (!$id) {
            return $this->response->setJSON(['error' => 'No ID provided']);
        }

        $role = $this->roleModel->find($id);
        if (!$role) {
            return $this->response->setJSON(['error' => 'Role not found']);
        }

        $validation = \Config\Services::validation();
        $rules = [
            'txtRoleName' => 'required|min_length[3]|max_length[50]',
            'txtRoleDesc' => 'required|min_length[3]|max_length[255]',
            'txtRoleNote' => 'permit_empty|max_length[500]',
            'bitActive'   => 'permit_empty'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return $this->response->setJSON(['errors' => $validation->getErrors()]);
        }

        $currentTime = date('Y-m-d H:i:s');
        $data = [
            'txtRoleName' => $this->request->getPost('txtRoleName'),
            'txtRoleDesc' => $this->request->getPost('txtRoleDesc'),
            'txtRoleNote' => $this->request->getPost('txtRoleNote'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => $currentTime
        ];

        if ($this->roleModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Role updated successfully']);
        } else {
            return $this->response->setJSON(['error' => 'Failed to update role']);
        }
    }

    public function view($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);
        $role = $this->roleModel->find($id);

        if (!$role) {
            return redirect()->to('/roles')
                ->with('message', 'Role not found')
                ->with('message_type', 'danger');
        }        
        
        // Set default values for nullable fields
        $role['bitActive'] = $role['bitActive'] ?? 1;
        $role['txtRoleDesc'] = $role['txtRoleDesc'] ?? '';
        $role['txtRoleNote'] = $role['txtRoleNote'] ?? '';
        $role['txtCreatedBy'] = $role['txtCreatedBy'] ?? 'System';
        $role['txtUpdatedBy'] = $role['txtUpdatedBy'] ?? '-';

        return view('role/view', [
            'role' => $role,
            'menus' => $menus,
            'title' => 'View Role',
            'pageTitle' => 'View Role',
            'pageSubTitle' => 'View role details',
            'icon' => 'eye'
        ]);
    }

    public function data()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid request']);
        }

        try {
            $draw = $this->request->getPost('draw');
            $start = $this->request->getPost('start');
            $length = $this->request->getPost('length');
            $search = $this->request->getPost('search')['value'];
            $order = $this->request->getPost('order')[0] ?? ['column' => 1, 'dir' => 'asc'];
            
            // Build the query
            $builder = $this->roleModel->builder();
            
            // Total records without filtering
            $totalRecords = $builder->countAllResults(false);

            // Apply search if any
            if (!empty($search)) {
                $builder->groupStart()
                    ->like('txtRoleName', $search)
                    ->orLike('txtRoleDesc', $search)
                    ->orLike('txtRoleNote', $search)
                    ->orLike('txtCreatedBy', $search)
                    ->orLike('txtUpdatedBy', $search)
                    ->groupEnd();
            }

            // Count filtered records
            $filteredRecords = $builder->countAllResults(false);

            // Ordering
            $columns = ['intRoleID', 'txtRoleName', 'txtRoleDesc', 'txtRoleNote', 'bitActive', 'txtCreatedBy', 'dtmCreatedDate', 'txtUpdatedBy', 'dtmUpdatedDate'];
            if (isset($order['column']) && isset($columns[$order['column']])) {
                $orderColumn = $columns[$order['column']];
                $builder->orderBy($orderColumn, $order['dir']);
            }

            // Fetch records
            $records = $builder->limit($length, $start)->get()->getResultArray();
            
            // Ensure all records have proper values
            $records = array_map(function($record) {
                return array_merge($record, [
                    'bitActive' => $record['bitActive'] ?? 1,
                    'txtCreatedBy' => $record['txtCreatedBy'] ?? 'System',
                    'txtUpdatedBy' => $record['txtUpdatedBy'] ?? '-'
                ]);
            }, $records);

            return $this->response->setJSON([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $records
            ]);

        } catch (\Exception $e) {
            log_message('error', '[RoleController::data] Error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
