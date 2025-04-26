<?php

namespace App\Controllers;

use App\Models\UserJobTitleCompetencyIndicatorModel;
use App\Models\MenuModel;
use App\Models\UserModel;
use App\Models\JobTitleModel;
use CodeIgniter\Controller;

class UserJobTitleCompetencyIndicatorController extends Controller
{
    protected $indicatorModel;

    public function __construct()
    {
        $this->indicatorModel = new UserJobTitleCompetencyIndicatorModel();
        helper('Auth'); // Pastikan helper dipanggil
    }

    public function index()
    {
        if ($redirect = checkLogin()) return $redirect;  // Cek login
        $menus = getRoleMenus();  // Ambil menu berdasarkan role

        // Ambil data indikator dengan detail
        $indicators = $this->indicatorModel->getIndicatorsWithDetails();

        // Kirim data ke view
        return view('UserJobTitleCompetencyIndicator/index', [
            'indicators' => $indicators,
            'menus' => $menus,
            'icon' => 'users',
            'pageTitle' => 'Master User Job Title', // Mengirimkan judul ke view
            'pageSubTitle' => 'Menampilkan daftar user job title', // Mengirimkan sub-judul ke view
            'cardTitle' => 'Menu'
        ]);
    }

    public function create()
    {
        if ($redirect = checkLogin()) return $redirect;  // Cek login
        $menus = getRoleMenus();  // Ambil menu berdasarkan role

        // Ambil data pengguna, job titles, competencies, indicators, dan lines
        $userModel = new \App\Models\UserModel();
        $users = $userModel->findAll();

        $jobTitleModel = new \App\Models\JobTitleModel();
        $jobTitles = $jobTitleModel->findAll();

        $competenciesModel = new \App\Models\CompetenciesModel();
        $competencies = $competenciesModel->findAll();

        $indicatorModel = new \App\Models\IndicatorModel();
        $indicators = $indicatorModel->findAll();

        $lineModel = new \App\Models\LineModel();
        $lines = $lineModel->findAll(); // Periksa hasilnya di sini

        // Kirim data ke view
        return view('UserJobTitleCompetencyIndicator/create', [
            'menus' => $menus,
            'users' => $users,
            'jobTitles' => $jobTitles,
            'competencies' => $competencies,
            'indicators' => $indicators,
            'lines' => $lines, // Kirim data line ke view tanpa array
            'icon' => 'users',
            'pageTitle' => 'Tr UserJobTitleCompetencyIndicator',
            'pageSubTitle' => 'Menampilkan daftar UserJobTitleCompetencyIndicator',
            'cardTitle' => 'UserJobTitleCompetencyIndicator'
        ]);
    }

    public function store()
    {
        $this->indicatorModel->save([
            'intUserID'       => $this->request->getPost('intUserID'),
            'intJobTitleID'   => $this->request->getPost('intJobTitleID'),
            'intCompetencyID' => $this->request->getPost('intCompetencyID'),
            'intIndicatorID'  => $this->request->getPost('intIndicatorID'),
            'intLineID'       => $this->request->getPost('intLineID'), // Menyimpan Line ID
            'bitAchieved'     => $this->request->getPost('bitAchieved'),
            'bitActive'       => $this->request->getPost('bitActive'),
            'txtGUID'         => uniqid(),
            'txtInsertedBy'   => session()->get('username'),
            'txtUpdatedBy'    => session()->get('username')
        ]);

        return redirect()->to('/UserJobTitleCompetencyIndicator');
    }

    public function view($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);

        $indicators = $this->indicatorModel->getIndicatorsWithDetails();
        $indicator = $this->indicatorModel->getIndicatorData($id);

        if ($indicator) {
            return view('UserJobTitleCompetencyIndicator/view', [
                'menus' => $menus,
                'indicator' => $indicator,
                'indicators' => $indicators,
                'pageTitle' => 'Master User Job Title',
                'pageSubTitle' => 'Menampilkan daftar user job title',
                'cardTitle' => 'Menu',
                'icon' => 'users'
            ]);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Indicator with ID $id not found");
        }
    }

    public function edit($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);

        // Ambil data untuk dropdown
        $userModel = new UserModel();
        $users = $userModel->findAll();

        $jobTitleModel = new JobTitleModel();
        $jobTitles = $jobTitleModel->findAll();

        $indicator = $this->indicatorModel->getIndicatorData($id);

        if ($indicator) {
            return view('UserJobTitleCompetencyIndicator/edit', [
                'menus' => $menus,
                'indicator' => $indicator,
                'users' => $users,
                'jobTitles' => $jobTitles,
                'pageTitle' => 'Edit Indicator',
                'pageSubTitle' => 'Edit data indicator dan informasi terkait',
                'cardTitle' => 'Edit Indicator',
                'icon' => 'edit'
            ]);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Indicator with ID $id not found");
        }
    }

    public function update($id)
    {
        $this->indicatorModel->update($id, [
            'intUserID'       => $this->request->getPost('intUserID'),
            'intJobTitleID'   => $this->request->getPost('intJobTitleID'),
            'intCompetencyID' => $this->request->getPost('intCompetencyID'),
            'intIndicatorID'  => $this->request->getPost('intIndicatorID'),
            'bitAchieved'     => $this->request->getPost('bitAchieved'),
            'bitActive'       => $this->request->getPost('bitActive'),
            'txtUpdatedBy'    => session()->get('username')
        ]);

        return redirect()->to('/UserJobTitleCompetencyIndicator');
    }

    public function delete($id)
    {
        if ($this->indicatorModel->delete($id)) {
            return redirect()->to('/UserJobTitleCompetencyIndicator')->with('success', 'Indicator deleted successfully.');
        } else {
            return redirect()->to('/UserJobTitleCompetencyIndicator')->with('error', 'Failed to delete indicator.');
        }
    }

    // Fungsi untuk mendapatkan kompetensi berdasarkan job title
    public function getCompetenciesByJobTitle($jobTitleId)
    {
        $competencyModel = new \App\Models\CompetencyModel();
        $competencies = $competencyModel->getCompetenciesByJobTitle($jobTitleId);

        return $this->response->setJSON($competencies);
    }

    public function getIndicatorsByCompetency($competencyID)
    {
        $indicatorModel = new \App\Models\IndicatorModel();
        // Ambil indikator berdasarkan kompetensi
        $indicators = $indicatorModel->where('intCompetencyID', $competencyID)->findAll();

        return $this->response->setJSON($indicators);
    }
}
