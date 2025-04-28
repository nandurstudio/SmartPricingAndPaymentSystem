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

    // UserController.php
    public function list()
    {
        $userModel = new \App\Models\MUserModel();

        // Mengambil semua pengguna dengan role mereka
        $data['users'] = $userModel->getUsersWithRole();

        return view('user/list', $data);
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

    // Menampilkan form untuk menambah user
    public function add()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Ambil semua role yang tersedia
        $roleModel = new MRoleModel();
        $roles = $roleModel->findAll();  // Ambil semua role

        // Data yang akan dikirim ke view
        $data = [
            'roles' => $roles,  // Semua role yang tersedia
        ];

        return view('user/add', $data); // Pastikan path view sesuai dengan struktur project
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

    // Memproses penambahan user
    public function store()
    {
        // Validasi input
        $validationRules = [
            'intRoleID' => [
                'label' => 'Role',
                'rules' => 'required|integer',
            ],
            'txtUserName' => [
                'label' => 'Username',
                'rules' => 'required|max_length[50]|is_unique[m_user.txtUserName]',
            ],
            'txtFullName' => [
                'label' => 'Full Name',
                'rules' => 'required|max_length[100]',
            ],
            'txtEmail' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[m_user.txtEmail]',
            ],
            'txtPassword' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
            ],
            'bitActive' => [
                'label' => 'Active Status',
                'rules' => 'required|in_list[0,1]',
            ],
            'txtPhoto' => [
                'label' => 'Photo',
                'rules' => 'permit_empty|is_image[txtPhoto]|max_size[txtPhoto,2048]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            // Log validasi gagal
            log_message('error', 'Validation failed: ' . print_r($this->validator->getErrors(), true));
            return redirect()->to('/user/add')->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data input form
        $data = [
            'txtUserName'   => $this->request->getPost('txtUserName'),
            'txtFullName'   => $this->request->getPost('txtFullName'),
            'txtEmail'      => $this->request->getPost('txtEmail'),
            'intRoleID'     => $this->request->getPost('intRoleID'),
            'bitActive'     => $this->request->getPost('bitActive'),
            'txtPassword'   => password_hash($this->request->getPost('txtPassword'), PASSWORD_DEFAULT),
            'txtCreatedBy'  => session()->get('userName'), // isi otomatis
            'dtmCreatedDate' => date('Y-m-d H:i:s'),        // isi otomatis
            'dtmJoinDate'    => date('Y-m-d H:i:s'),        // isi otomatis join date sama dengan created date
        ];

        // Hash password
        $data['txtPassword'] = password_hash($this->request->getPost('txtPassword'), PASSWORD_DEFAULT);

        // Log data yang akan ditambahkan
        log_message('debug', 'Data to insert: ' . print_r($data, true));

        // Proses upload foto jika ada
        $photo = $this->request->getFile('txtPhoto');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if (in_array($photo->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                $newName = $photo->getRandomName();
                $photo->move(ROOTPATH . 'public/uploads/photos', $newName);
                $data['txtPhoto'] = $newName;
            } else {
                log_message('error', 'Invalid file type: ' . $photo->getMimeType());
                session()->setFlashdata('error', 'The uploaded file must be an image (jpg/png/gif).');
                return redirect()->to('/user/add')->withInput();
            }
        }

        // Insert data user baru
        if (!$this->userModel->insert($data)) {
            log_message('error', 'Failed to insert user. Data: ' . print_r($data, true));
            session()->setFlashdata('error', 'Failed to add user.');
            return redirect()->to('/user/add');
        }

        session()->setFlashdata('success', 'User added successfully!');
        return redirect()->to('/user/list');
    }


    // Menampilkan form edit user
    public function edit($userID)
    {
        $user = $this->userModel->find($userID);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        // Ambil role berdasarkan ID
        $roleModel = new MRoleModel();
        $role = $roleModel->find($user['intRoleID']); // Ambil role spesifik user

        // Ambil semua role yang tersedia
        $roles = $roleModel->findAll();  // Ambil semua role

        // Data yang akan dikirim ke view
        $data = [
            'user' => $user,
            'role' => $role,   // Role saat ini yang dimiliki user
            'roles' => $roles, // Semua role yang tersedia
        ];

        return view('user/edit', $data); // Pastikan path view sesuai dengan struktur project
    }

    // Memproses update user
    public function update($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        // Validasi input
        $validationRules = [
            'intRoleID' => [
                'label' => 'Role',
                'rules' => 'required|integer',
            ],
            'txtUserName' => [
                'label' => 'Username',
                'rules' => 'required|max_length[50]',
            ],
            'txtFullName' => [
                'label' => 'Full Name',
                'rules' => 'required|max_length[100]',
            ],
            'txtEmail' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
            ],
            'txtPassword' => [
                'label' => 'Password',
                'rules' => 'permit_empty|min_length[6]',
            ],
            'bitActive' => [
                'label' => 'Active Status',
                'rules' => 'required|in_list[0,1]',
            ],
            'txtPhoto' => [
                'label' => 'Photo',
                'rules' => 'permit_empty|is_image[txtPhoto]|max_size[txtPhoto,2048]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            // Log validasi gagal
            log_message('error', 'Validation failed: ' . print_r($this->validator->getErrors(), true));
            return redirect()->to('/user/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data input form
        $data = [
            'txtUserName'   => $this->request->getPost('txtUserName'),
            'txtFullName'   => $this->request->getPost('txtFullName'),
            'txtEmail'      => $this->request->getPost('txtEmail'),
            'bitActive' => $this->request->getPost('bitActive'),
            'intRoleID'     => $this->request->getPost('intRoleID'),
            'txtUpdatedBy'  => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s'),
        ];

        // Log data yang akan diupdate
        log_message('debug', 'Data to update: ' . print_r($data, true));

        // Update password jika diisi
        $password = $this->request->getPost('txtPassword');
        if (!empty($password)) {
            $data['txtPassword'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $photo = $this->request->getFile('txtPhoto');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            if (in_array($photo->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                $newName = $photo->getRandomName();
                $photo->move(ROOTPATH . 'public/uploads/photos', $newName);
                $data['txtPhoto'] = $newName;
            } else {
                log_message('error', 'Invalid file type: ' . $photo->getMimeType());
                session()->setFlashdata('error', 'The uploaded file must be an image (jpg/png/gif).');
                return redirect()->to('/user/edit/' . $id)->withInput();
            }
        }

        // Update data user
        if (!$this->userModel->update($id, $data)) {
            log_message('error', 'Failed to update user. Data: ' . print_r($data, true));
            session()->setFlashdata('error', 'Failed to update user.');
            return redirect()->to('/user/edit/' . $id);
        }

        session()->setFlashdata('success', 'User updated successfully!');
        return redirect()->to('/user/list');
    }
}
