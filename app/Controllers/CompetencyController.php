<?php

namespace App\Controllers;

use App\Models\CompetencyModel;
use CodeIgniter\Controller;

class CompetencyController extends Controller
{
    protected $competencyModel;

    public function __construct()
    {
        $this->competencyModel = new CompetencyModel();
    }

    public function index()
    {
        $competencyModel = new CompetencyModel();
        $data['competencies'] = $competencyModel->findAll();

        return view('competency/index', $data);
    }

    public function create()
    {
        // Menampilkan form untuk membuat competency baru
        return view('competency/create');
    }

    public function store()
    {
        // Menyimpan competency baru
        $data = [
            'intCompetencyID' => $this->request->getPost('intCompetencyID'),
            'intJobTitleID' => $this->request->getPost('intJobTitleID'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtInsertedBy' => session()->get('userNick'), // Ambil dari session
            'txtUpdatedBy' => session()->get('userNick'), // Ambil dari session
        ];

        $this->competencyModel->save($data);
        return redirect()->to('/competency');
    }

    public function edit($intCompetencyID, $intJobTitleID)
    {
        $competencyModel = new CompetencyModel();

        // Ambil data berdasarkan composite key
        $competency = $competencyModel->where('intCompetencyID', $intCompetencyID)
            ->where('intJobTitleID', $intJobTitleID)
            ->first();

        if (!$competency) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Competency not found");
        }

        return view('competency/edit', ['competency' => $competency]);
    }

    public function update($competencyId)
    {
        // Memperbarui data competency
        $jobTitleId = 1; // Ganti dengan ID Job Title yang sesuai
        $data = [
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => session()->get('userNick'), // Ambil dari session
        ];

        $this->competencyModel->update([$competencyId, $jobTitleId], $data);
        return redirect()->to('/competency');
    }

    public function view($intCompetencyID, $intJobTitleID)
    {
        $competencyModel = new CompetencyModel();

        // Mengambil data competency berdasarkan intCompetencyID dan intJobTitleID
        $data['competency'] = $competencyModel->where('intCompetencyID', $intCompetencyID)
            ->where('intJobTitleID', $intJobTitleID)
            ->first();

        // Load view
        return view('competency/view', $data);
    }

    public function getCompetencies()
    {
        // Load model
        $competencyModel = new \App\Models\CompetencyModel();

        // Ambil data dengan pagination dan filtering
        $draw = $this->request->getVar('draw');
        $start = $this->request->getVar('start');
        $length = $this->request->getVar('length');
        $searchValue = $this->request->getVar('search')['value'];

        $competencies = $competencyModel->getCompetencies($start, $length, $searchValue);
        $totalRecords = $competencyModel->countAll();
        $totalFiltered = $competencyModel->countAllCompetencies($searchValue); // Hitung total records yang terfilter

        $data = [
            'draw' => intval($draw),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $competencies,
        ];

        return $this->response->setJSON($data);
    }
}
