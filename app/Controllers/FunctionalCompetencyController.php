<?php

namespace App\Controllers;

use App\Models\FunctionalCompetencyModel;
use App\Models\MenuModel;
use App\Models\CompetenciesModel;
use App\Models\CompetencyModel;
use App\Models\IndicatorModel;
use App\Models\JobTitleModel;
use App\Models\LineModel;
use App\Models\ToolModel;

class FunctionalCompetencyController extends BaseController
{
    // Menampilkan daftar Functional Competency
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $competencyModel = new FunctionalCompetencyModel();
        $competencies = $competencyModel->findAll();

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        // Ganti MenuModel menjadi MenusModel
        $menusModel = new MenuModel();  // Menyesuaikan dengan model baru
        $menus = $menusModel->getMenusByRole($roleID);  // Memanggil method dari MenusModel

        return view('functional_competency/index', [
            'menus' => $menus,
            'competencies' => $competencies,
            'icon' => 'list',
            'pageSubTitle' => 'Daftar Functional Competency',
            'cardTitle' => 'Functional Competency',
            'pageTitle' => 'Master Functional Competency'
        ]);
    }

    // Menampilkan form untuk membuat Functional Competency baru
    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Ambil data dari model terkait
        $competenciesModel = new CompetenciesModel();
        $jobTitlesModel = new JobTitleModel();
        $linesModel = new LineModel();
        $toolsModel = new ToolModel();

        // Ambil data untuk dropdown
        $competencies = $competenciesModel->findAll();
        $jobTitles = $jobTitlesModel->findAll();
        $lines = $linesModel->findAll();
        $tools = $toolsModel->findAll();

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        // Ganti MenuModel menjadi MenusModel
        $menusModel = new MenuModel();  // Menyesuaikan dengan model baru
        $menus = $menusModel->getMenusByRole($roleID);  // Memanggil method dari MenusModel

        return view('functional_competency/create', [
            'competencies' => $competencies,
            'jobTitles' => $jobTitles,
            'lines' => $lines,
            'tools' => $tools,
            'menus' => $menus,
            'icon' => 'plus',
            'pageSubTitle' => 'Tambah Functional Competency',
            'cardTitle' => 'Tambah Functional Competency',
            'pageTitle' => 'Tambah Functional Competency'
        ]);
    }

    public function getCompetencyDefinition($competencyID)
    {
        $competenciesModel = new CompetenciesModel();
        $competency = $competenciesModel->find($competencyID);
        return $this->response->setJSON(['txtDefinition' => $competency ? $competency['txtDefinition'] : '']);
    }

    public function getIndicators($competencyID)
    {
        $indicatorsModel = new IndicatorModel();
        $indicators = $indicatorsModel->getIndicatorsByCompetency($competencyID);
        return $this->response->setJSON($indicators);
    }

    public function getCompetencies($jobtitleID)
    {
        $competenciesModel = new CompetencyModel();
        $competencies = $competenciesModel->getCompetenciesByJobTitle($jobtitleID);
        return $this->response->setJSON($competencies);
    }

    // Menyimpan data Functional Competency baru ke database
    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error',
                'You must be logged in to access this page.'
            );
        }

        $competencyModel = new FunctionalCompetencyModel();
        $jobTitleModel = new JobTitleModel();
        $lineModel = new LineModel();
        $toolModel = new ToolModel();

        // Validasi input data
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'intCompetencyID' => 'required|is_not_unique[mCompetency.intCompetencyID]',
            'intToolID' => 'required|is_not_unique[mTool.intToolID]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->to('/functionalcompetency/create')->withInput()->with('error', 'Validation failed.');
        }

        // Ambil data input
        $competencyData = [
                'intCompetencyID' => $this->request->getPost('intCompetencyID'),
                'txtDefinition' => $this->request->getPost('txtDefinition'),
                'txtInsertedBy' => session()->get('username'),
                'dtmInsertedDate' => date('Y-m-d H:i:s'),
                'bitActive' => $this->request->getPost('bitActive') ? 1 : 0, // Handle active checkbox
            ];

        // Simpan data competency
        if ($competencyModel->save($competencyData)) {
            // Ambil ID competency yang baru disimpan
            $competencyID = $competencyModel->getInsertID();

            // Simpan job titles yang terkait
            $jobTitleIDs = $this->request->getPost('intJobTitleIDs') ?? [];
            foreach ($jobTitleIDs as $jobTitleID) {
                $competencyJobData[] = [
                    'intCompetencyID' => $competencyID,
                    'intJobTitleID' => $jobTitleID,
                ];
            }
            if (!empty($competencyJobData)) {
                $competencyModel->insertBatch($competencyJobData); // Simpan job titles ke relasi
            }

            // Simpan lines yang terkait
            $lineIDs = $this->request->getPost('intLineIDs') ?? [];
            foreach ($lineIDs as $lineID) {
                $competencyLineData[] = [
                    'intCompetencyID' => $competencyID,
                    'intLineID' => $lineID,
                ];
            }
            if (!empty($competencyLineData)) {
                $competencyModel->insertBatch($competencyLineData); // Simpan lines ke relasi
            }

            // Simpan tools yang terkait
            $toolID = $this->request->getPost('intToolID');
            if ($toolID) {
                $competencyToolData = [
                    'intCompetencyID' => $competencyID,
                    'intToolID' => $toolID,
                ];
                $competencyModel->insert($competencyToolData); // Simpan tool ke relasi
            }

            return redirect()->to('/functionalcompetency')->with('success', 'Functional Competency added successfully.');
        } else {
            return redirect()->to('/functionalcompetency/create')->with('error', 'Failed to add Functional Competency.');
        }
    }

    // Menampilkan detail Functional Competency berdasarkan ID
    public function details($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $competencyModel = new FunctionalCompetencyModel();
        $competency = $competencyModel->find($id);

        if (!$competency) {
            return redirect()->to('/functionalcompetency')->with('error', 'Functional Competency not found.');
        }

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        // Ganti MenuModel menjadi MenusModel
        $menusModel = new MenuModel();  // Menyesuaikan dengan model baru
        $menus = $menusModel->getMenusByRole($roleID);  // Memanggil method dari MenusModel

        return view('functional_competency/details', [
            'menus' => $menus,
            'competency' => $competency,
            'icon' => 'eye',
            'pageSubTitle' => 'Detail Functional Competency',
            'cardTitle' => 'Functional Competency Details',
            'pageTitle' => 'Functional Competency Details'
        ]);
    }

    // Menampilkan form untuk mengedit Functional Competency berdasarkan ID
    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $competencyModel = new FunctionalCompetencyModel();
        $competency = $competencyModel->find($id);

        if (!$competency) {
            return redirect()->to('/functionalcompetency')->with('error', 'Functional Competency not found.');
        }

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        // Ganti MenuModel menjadi MenusModel
        $menusModel = new MenuModel();  // Menyesuaikan dengan model baru
        $menus = $menusModel->getMenusByRole($roleID);  // Memanggil method dari MenusModel

        return view('functional_competency/edit', [
            'menus' => $menus,
            'competency' => $competency,
            'icon' => 'edit',
            'pageSubTitle' => 'Edit Functional Competency',
            'cardTitle' => 'Edit Functional Competency',
            'pageTitle' => 'Edit Functional Competency'
        ]);
    }

    // Memperbarui data Functional Competency berdasarkan ID
    public function update($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $competencyModel = new FunctionalCompetencyModel();
        $competency = $competencyModel->find($id);

        if (!$competency) {
            return redirect()->to('/functionalcompetency')->with('error', 'Functional Competency not found.');
        }

        // Validasi input data
        $validation = \Config\Services::validation();
        $validation->setRules([
            'txtCompetency' => 'required|min_length[3]|max_length[100]',
            'txtDefinition' => 'required|min_length[5]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->to('/functionalcompetency/edit/' . $id)->withInput()->with('error', 'Validation failed.');
        }

        // Ambil data input
        $competencyData = [
            'txtCompetency' => $this->request->getPost('txtCompetency'),
            'txtDefinition' => $this->request->getPost('txtDefinition'),
            'txtUpdatedBy' => session()->get('username'),
        ];

        // Update ke database
        if ($competencyModel->update($id, $competencyData)) {
            return redirect()->to('/functionalcompetency')->with('success', 'Functional Competency updated successfully.');
        } else {
            return redirect()->to('/functionalcompetency/edit/' . $id)->with('error', 'Failed to update Functional Competency.');
        }
    }
}
