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
                'bitStatus' => $role['bitStatus'] ?? 1,
                'txtRoleDesc' => $role['txtRoleDesc'] ?? '',
                'txtCreatedBy' => $role['txtCreatedBy'] ?? 'System',
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
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }
        
        // Validation rules
        $validation = \Config\Services::validation();
        $rules = [
            'txtRoleName' => 'required|min_length[3]|max_length[50]',
            'txtRoleDesc' => 'required|min_length[3]|max_length[255]',
            'txtRoleNote' => 'permit_empty|max_length[500]',
            'bitStatus'   => 'permit_empty'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $currentTime = date('Y-m-d H:i:s');
        $this->roleModel->save([
            'txtRoleName' => $this->request->getPost('txtRoleName'),
            'txtRoleDesc' => $this->request->getPost('txtRoleDesc'),
            'txtRoleNote' => $this->request->getPost('txtRoleNote'),
            'bitStatus' => $this->request->getPost('bitStatus') ? 1 : 0,
            'txtCreatedBy' => session()->get('userName') ?? 'system',
            'dtmCreatedDate' => $currentTime,
            'txtGUID' => uniqid('role_', true),
        ]);

        return redirect()->to('/roles')
            ->with('message', 'Role successfully created')
            ->with('message_type', 'success');
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
        $role['bitStatus'] = $role['bitStatus'] ?? 1;
        $role['txtRoleDesc'] = $role['txtRoleDesc'] ?? '';

        return view('role/edit', [
            'role' => $role,
            'menus' => $menus,
            'title' => 'Edit Role',
            'pageTitle' => 'Edit Role',
            'pageSubTitle' => 'Modify role information',
            'icon' => 'edit'
        ]);
    }    public function update($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Validation rules 
        $validation = \Config\Services::validation();
        $rules = [
            'txtRoleName' => 'required|min_length[3]|max_length[50]',
            'txtRoleDesc' => 'required|min_length[3]|max_length[255]',
            'txtRoleNote' => 'permit_empty|max_length[500]',
            'bitStatus'   => 'permit_empty'
        ];

        $postData = $this->request->getPost();
        if (!$validation->setRules($rules)->run($postData)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Prepare data
        $data = [
            'txtRoleName' => $this->request->getPost('txtRoleName'),
            'txtRoleDesc' => $this->request->getPost('txtRoleDesc'),
            'txtRoleNote' => $this->request->getPost('txtRoleNote'),
            'bitStatus'   => $this->request->getPost('bitStatus') ? 1 : 0,
            'txtLastUpdatedBy' => session()->get('userName'),
            'dtmLastUpdatedDate' => date('Y-m-d H:i:s')
        ];

        try {
            $this->roleModel->update($id, $data);
            session()->setFlashdata('success', 'Role berhasil diperbarui.');
            return redirect()->to('/roles');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error updating role: ' . $e->getMessage());
            return redirect()->back()->withInput();
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
            return redirect()->to('/roles')->with('message', 'Role not found')->with('message_type', 'danger');
        }        // Set default values for nullable fields
        $role['bitStatus'] = $role['bitStatus'] ?? 1;
        $role['txtRoleDesc'] = $role['txtRoleDesc'] ?? '';
        $role['txtCreatedBy'] = $role['txtCreatedBy'] ?? 'System';
        $role['txtLastUpdatedBy'] = $role['txtLastUpdatedBy'] ?? '-';

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
            $order = $this->request->getPost('order')[0] ?? ['column' => 1, 'dir' => 'asc'];            // Build the query
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
                    ->orLike('txtLastUpdatedBy', $search)
                    ->groupEnd();
            }

            // Count filtered records
            $filteredRecords = $builder->countAllResults(false);

            // Ordering
            $columns = ['intRoleID', 'txtRoleName', 'txtRoleDesc', 'txtRoleNote', 'bitStatus', 'txtCreatedBy', 'dtmCreatedDate', 'txtLastUpdatedBy', 'dtmLastUpdatedDate'];
            if (isset($order['column']) && isset($columns[$order['column']])) {
                $orderColumn = $columns[$order['column']];
                $builder->orderBy($orderColumn, $order['dir']);
            }

            // Fetch records
            $records = $builder->limit($length, $start)->get()->getResultArray();
              // Ensure all records have proper values
            $records = array_map(function($record) {
                return array_merge($record, [
                    'bitStatus' => $record['bitStatus'] ?? 1,
                    'txtCreatedBy' => $record['txtCreatedBy'] ?? 'System',
                    'txtLastUpdatedBy' => $record['txtLastUpdatedBy'] ?? '-'
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
