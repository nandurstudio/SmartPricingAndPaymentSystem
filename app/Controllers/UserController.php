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

    public function __construct()
    {
        $this->userModel = new MUserModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');

        // Ambil menu berdasarkan role
        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);

        // Set pagination parameters
        $perPage = 10; // Number of users per page
        $currentPage = $this->request->getVar('page') ?? 1; // Get current page, default to 1 if not set

        // Fetch users with pagination
        $users = $this->userModel->paginate($perPage, 'default', $currentPage);

        // Get pager object
        $pager = $this->userModel->pager;

        // Fetch Role names for each user
        $roleModel = new MRoleModel();
        foreach ($users as &$user) {
            $user['txtRoleName'] = $roleModel->find($user['intRoleID'])['txtRoleName'];
        }

        return view('user/index', [
            'menus' => $menus,
            'users' => $users,
            'pager' => $pager, // Pass pager object to the view
            'pageTitle' => 'Daftar User',
            'pageSubTitle' => 'Menampilkan daftar user dan employee',
            'cardTitle' => 'Users',
            'icon' => 'users',
            'scripts' => 'assets/js/pages/user.js' // Kirim nama file script
        ]);
    }

    public function getUsers()
    {
        try {
            $model = new MUserModel();

            // Ambil parameter dari request
            $draw = (int)$this->request->getVar('draw');
            $start = (int)$this->request->getVar('start');
            $length = (int)$this->request->getVar('length');
            $searchValue = $this->request->getVar('search')['value'];

            // Ambil parameter order untuk sorting
            $order = $this->request->getVar('order');
            $orderColumnIndex = isset($order[0]['column']) ? (int)$order[0]['column'] : 0; // Default ke kolom pertama jika null
            $orderDirection = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc'; // Default ke 'asc' jika null

            // Daftar kolom yang bisa diurutkan (sesuaikan dengan struktur kolom di frontend)
            $columns = ['intUserID', 'txtUserName', 'txtFullName', 'txtEmail', 'txtRoleName', 'txtNick', 'txtEmpID', 'txtPhoto', 'dtmJoinDate', 'bitActive'];

            // Tentukan kolom yang digunakan untuk pengurutan
            $orderBy = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'txtFullName';

            // Mengambil data kompetensi dengan pengurutan
            $users = $model->getUsers($start, $length, $searchValue, $orderBy, $orderDirection);

            // Menghitung total records yang ada
            $totalRecords = $model->countAllUsers($searchValue);

            // Membuat respons
            $data = [
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $users,
            ];

            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    // Menampilkan 7 pengguna terakhir
    public function getLastUsers()
    {
        // Inisialisasi model
        $userModel = new MUserModel();

        // Log untuk memastikan bahwa model sudah diinisialisasi
        log_message('debug', 'UserModel initialized');

        // Ambil 7 pengguna terakhir berdasarkan login atau registrasi
        $users = $userModel->getUsersBasedOnLoginOrRegister(0, 7);

        // Log untuk memastikan query berhasil dan data $users terisi
        log_message('debug', 'Fetched Users: ' . print_r($users, true));

        // Cek apakah data pengguna kosong
        if (empty($users)) {
            log_message('debug', 'No users found.');
        } else {
            log_message('debug', 'Users retrieved successfully.');
        }

        // Kirim data pengguna ke view dengan menggunakan array
        return view('user/index', [
            'users' => $users
        ]);
    }

    // CREATE - Menampilkan form tambah user
    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);
        
        // Fetching roles, job titles, supervisors, lines, and departments for the form
        $roleModel = new MRoleModel();
        $roles = $roleModel->findAll();

        $userModel = new MUserModel();
        $supervisors = $userModel->findAll();  // Get all users as supervisors

        return view('user/create', [
            'menus' => $menus,
            'roles' => $roles,
            'supervisors' => $supervisors,
            'pageTitle' => 'Create User',
            'pageSubTitle' => 'Add a new user to the system',
            'cardTitle' => 'Create User',
            'icon' => 'user-plus'
        ]);
    }

    // STORE - Proses penyimpanan user baru
    public function store()
    {
        // Validasi form
        $validationRules = [
            'txtUserName'     => 'required|min_length[3]|max_length[50]',
            'txtFullName'     => 'required|min_length[3]|max_length[100]',
            'txtEmail'        => 'required|valid_email|is_unique[users.txtEmail]',
            'txtPassword'     => 'required|min_length[8]',
            'intRoleID'       => 'required|integer',
            'intJobTitleID'   => 'required|integer',
            'intSupervisorID' => 'permit_empty|integer',
            'intLineID'       => 'required|integer',
            'intDepartmentID' => 'required|integer',
            'dtmJoinDate'     => 'required|valid_date[Y-m-d H:i:s]',
            'bitActive'       => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data input form
        $data = [
            'intRoleID'       => $this->request->getPost('intRoleID'),
            'intJobTitleID'   => $this->request->getPost('intJobTitleID'),
            'intSupervisorID' => $this->request->getPost('intSupervisorID'),
            'intLineID'       => $this->request->getPost('intLineID'),
            'intDepartmentID' => $this->request->getPost('intDepartmentID'),
            'txtUserName'     => $this->request->getPost('txtUserName'),
            'txtFullName'     => $this->request->getPost('txtFullName'),
            'txtNick'         => $this->request->getPost('txtNick', FILTER_SANITIZE_STRING) ?: 'DUM',  // Default value
            'txtEmpID'        => $this->request->getPost('txtEmpID'),
            'txtEmail'        => $this->request->getPost('txtEmail', FILTER_SANITIZE_EMAIL) ?: 'dummy@email.com', // Default value
            'txtPassword'     => Encrypt::encryptPassword($this->request->getPost('txtPassword')),
            'bitActive'       => $this->request->getPost('bitActive', FILTER_VALIDATE_BOOLEAN) ?: 1, // Default 1
            'txtInsertedBy'   => session()->get('userID'),
            'txtGUID'         => uniqid(),
            'txtPhoto'        => $this->request->getPost('txtPhoto') ?? 'default.jpg',
            'dtmJoinDate'     => $this->request->getPost('dtmJoinDate') ?: date('Y-m-d H:i:s'), // Default current date
        ];

        // Insert the new user data into the database
        if ($this->userModel->insert($data)) {
            return redirect()->to('/user')->with('success', 'User created successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    // SHOW - Menampilkan detail user berdasarkan ID
    public function view($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);

        $user = $this->userModel->find($id);

        if ($user) {
            // Ambil role dan supervisor berdasarkan ID
            $roleModel = new MRoleModel(); // Misalnya ada model untuk role
            $role = $roleModel->find($user['intRoleID']); // Ambil nama role berdasarkan ID

            $supervisorName = $this->userModel->find($user['intSupervisorID']); // Ambil supervisor jika ada

            return view('user/view', [
                'menus' => $menus,
                'user' => $user,
                'role' => $role, // Pastikan role di-pass ke view
                'supervisorName' => $supervisorName ? $supervisorName['txtFullName'] : 'No Supervisor', // Jika supervisor ada
                'pageTitle' => 'Daftar User',
                'pageSubTitle' => 'Menampilkan daftar user dan employee',
                'cardTitle' => 'Users',
                'icon' => 'users'
            ]);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User with ID $id not found");
        }
    }

    // EDIT - Menampilkan form edit user
    public function edit($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);

        // Fetch the required data for dropdowns
        $roleModel = new MRoleModel();
        $roles = $roleModel->findAll();

        $userModel = new MUserModel();
        $supervisors = $userModel->findAll();  // Get all users as supervisors

        $user = $userModel->find($id);

        if ($user) {
            return view('user/update', [
                'menus' => $menus,
                'user' => $user,
                'roles' => $roles,
                'supervisors' => $supervisors,
                'pageTitle' => 'Edit User',
                'pageSubTitle' => 'Edit data user dan informasi',
                'cardTitle' => 'Edit User',
                'icon' => 'edit'
            ]);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User with ID $id not found");
        }
    }

    // UPDATE - Proses update data user
    public function update($id = null)
    {
        $user = $this->userModel->find($id); // Ambil data user lama
        $photo = $this->request->getFile('txtPhoto');
        $newName = $user['txtPhoto']; // Default tetap pakai foto lama

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Path foto lama
            $oldPhotoPath = FCPATH . 'uploads/photos/' . $user['txtPhoto'];

            // Ambil NIK (txtEmpID) dan generate nama file baru
            $nik = $user['txtEmpID'];
            $datePrefix = date('dmy_His'); // Format tanggal dan waktu: ddmmyy_hhmmss
            $newName = $nik . '_' . $datePrefix . '.' . $photo->getExtension(); // Nama file baru

            // Pindahkan foto baru ke folder uploads
            $photo->move(FCPATH . 'uploads/photos', $newName);

            // Hapus foto lama jika ada dan bukan default
            if (!empty($user['txtPhoto']) && file_exists($oldPhotoPath) && $user['txtPhoto'] !== 'default.jpg') {
                unlink($oldPhotoPath); // Hapus file lama
            }
        }

        $data = [
            'intRoleID'       => $this->request->getVar('intRoleID'),
            'intJobTitleID'   => $this->request->getVar('intJobTitleID'),
            'intSupervisorID' => $this->request->getVar('intSupervisorID'),
            'intLineID'       => $this->request->getVar('intLineID'),
            'intDepartmentID' => $this->request->getVar('intDepartmentID'),
            'txtUserName'     => $this->request->getVar('txtUserName'),
            'txtFullName'     => $this->request->getVar('txtFullName'),
            'txtNick'         => $this->request->getVar('txtNick'),
            'txtEmpID'        => $this->request->getVar('txtEmpID'),
            'txtEmail'        => $this->request->getVar('txtEmail'),
            'txtUpdatedBy'    => session()->get('userID'),
            'bitActive'       => $this->request->getVar('bitActive') ? 1 : 0, // Handle bitActive checkbox
            'txtPhoto'        => $newName, // Simpan nama file baru
        ];

        // Handle join date (ensure correct format)
        $joinDate = $this->request->getVar('dtmJoinDate');
        if ($joinDate) {
            $data['dtmJoinDate'] = date('Y-m-d H:i:s', strtotime($joinDate)); // Format to 'YYYY-MM-DD HH:MM:SS'
        }

        // Handle password update jika ada
        $password = $this->request->getVar('txtPassword');
        if (!empty($password)) {
            $data['txtPassword'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Update user data
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/user')->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }
}
