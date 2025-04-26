<?php

// File: app/Controllers/CompetenciesController.php

namespace App\Controllers;

use App\Models\CompetenciesModel;
use App\Models\MenuModel;

class CompetenciesController extends BaseController
{
    protected $competenciesModel;

    public function __construct()
    {
        $this->competenciesModel = new CompetenciesModel();
    }

    // INDEX - Menampilkan daftar user
    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);

        // Ambil data kompetensi
        $compModel = new CompetenciesModel();
        $comps = $compModel->findAll();

        // Kirim URL API untuk competencies
        return view('competencies/index', [
            'menus' => $menus,
            'competencies' => $comps,
            'icon' => 'users',
            'pageSubTitle' => 'Menampilkan daftar Competencies',
            'cardTitle' => 'Competencies',
            'pageTitle' => 'Master Competency',
            'scripts' => 'assets/js/pages/competencies.js' // Kirim nama file script
        ]);
    }

    public function getCompetencies()
    {
        try {
            $model = new CompetenciesModel();

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
            $columns = ['intCompetencyID', 'txtCompetency', 'bitActive', 'txtInsertedBy', 'dtmInsertedDate', 'txtUpdatedBy', 'dtmUpdatedDate'];

            // Tentukan kolom yang digunakan untuk pengurutan
            $orderBy = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'intCompetencyID';

            // Mengambil data kompetensi dengan pengurutan
            $competencies = $model->getCompetencies($start, $length, $searchValue, $orderBy, $orderDirection);

            // Menghitung total records yang ada
            $totalRecords = $model->countAllCompetencies($searchValue);

            // Membuat respons
            $data = [
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $competencies,
            ];

            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('competencies/create');
    }

    public function store()
    {
        // Validasi input
        $validation = $this->validate([
            'txtCompetency' => 'required|is_unique[mCompetencies.txtCompetency]',
            'txtDefinition' => 'permit_empty|string',
            'bitActive' => 'permit_empty|in_list[0,1]',
            'txtGUID' => 'required'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data
        $this->competenciesModel->insert([
            'txtCompetency' => $this->request->getVar('txtCompetency'),
            'txtDefinition' => $this->request->getVar('txtDefinition'),
            'bitActive' => $this->request->getVar('bitActive'),
            'txtInsertedBy' => session()->get('username'),
            'txtGUID' => $this->request->getVar('txtGUID')
        ]);

        return redirect()->to('/competencies')->with('success', 'Competency created successfully.');
    }

    public function edit($id)
    {
        $competency = $this->competenciesModel->find($id);

        if (!$competency) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Competency not found');
        }

        return $this->response->setJSON($competency);
    }

    public function update($id)
    {
        $data = [
            'txtCompetency' => $this->request->getPost('txtCompetency'),
            'txtDefinition' => $this->request->getPost('txtDefinition'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => 'system', // Atau ambil dari session user jika ada
        ];

        // Update data
        $this->competenciesModel->update($id, $data);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Competency updated successfully.']);
    }

    public function view($id)
    {
        $competency = $this->competenciesModel->find($id);

        if (!$competency) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Competency not found');
        }

        return view('competencies/view', ['competency' => $competency]);
    }
}
