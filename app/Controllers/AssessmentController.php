<?php

namespace App\Controllers;

use App\Models\AssessmentModel;
use App\Models\CompetencyProgressModel;
use App\Models\MenuModel;
use CodeIgniter\RESTful\ResourceController;

class AssessmentController extends ResourceController
{
    protected $assessmentModel;
    protected $menuModel;
    protected $progressModel;

    public function __construct()
    {
        $this->assessmentModel = new AssessmentModel();
        $this->menuModel = new MenuModel();
        $this->progressModel = new CompetencyProgressModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $assessments = $this->assessmentModel->findAll();
        $menus = $this->menuModel->getMenusByRole(session()->get('roleID')); // Menggunakan dependency injection

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        // Ganti MenuModel menjadi MenusModel
        $menuModel = new MenuModel();  // Menyesuaikan dengan model baru
        $menus = $menuModel->getMenusByRole($roleID);  // Memanggil method dari MenusModel

        return view('assessment/index', [
            'menus' => $menus,
            'assessments' => $assessments,
            'icon' => 'list',
            'pageSubTitle' => 'Daftar Functional Competency',
            'cardTitle' => 'Functional Competency',
            'pageTitle' => 'Master Functional Competency'
        ]);
    }

    // Create Assessment
    public function create()
    {
        return view('assessment/create', [
            'pageTitle' => 'Create Assessment'
        ]);
    }

    public function store()
    {
        $data = [
            'intUserID'       => $this->request->getPost('intUserID'),
            'intLineID'       => $this->request->getPost('intLineID'),
            'intJobTitleID'   => $this->request->getPost('intJobTitleID'),
            'intCompetencyID' => $this->request->getPost('intCompetencyID'),
            'intIndicatorID'  => $this->request->getPost('intIndicatorID'),
            'bitResult'       => $this->request->getPost('bitResult'),
            'dtmAssessedDate' => date('Y-m-d H:i:s'),
            'txtAssessedBy'   => session()->get('username') ?? 'system',
            'txtGUID'         => uniqid()
        ];

        // Di dalam method store dan update
        $rules = [
                'intUserID'       => 'required|integer',
                'intLineID'       => 'required|integer',
                'intJobTitleID'   => 'required|integer',
                'intCompetencyID' => 'required|integer',
                'intIndicatorID'  => 'required|integer',
                'bitResult'       => 'required|in_list[0,1]'
            ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validation failed.')->withInput();
        }

        try {
            if ($this->assessmentModel->insert($data)) {
                $this->updateProgress($data['intUserID'], $data['intCompetencyID']);
                return redirect()->to('/assessment')->with('success', 'Assessment created successfully.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating assessment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create assessment.')->withInput();
        }
        
    }

    public function edit($id = null)
    {
        $assessment = $this->assessmentModel->find($id);

        if (!$assessment) {
            return redirect()->to('/assessment')->with('error', 'Assessment not found.');
        }

        return view('assessment/edit', [
            'pageTitle' => 'Edit Assessment',
            'assessment' => $assessment
        ]);
    }

    // View Assessment
    public function view($id = null)
    {
        $assessment = $this->assessmentModel->find($id);

        if (!$assessment) {
            return redirect()->to('/assessment')->with('error', 'Assessment not found.');
        }

        return view('assessment/view', ['assessment' => $assessment]);
    }

    // Update Assessment
    public function update($id = null)
    {
        $data = [
            'intUserID'       => $this->request->getPost('intUserID'),
            'intLineID'       => $this->request->getPost('intLineID'),
            'intJobTitleID'   => $this->request->getPost('intJobTitleID'),
            'intCompetencyID' => $this->request->getPost('intCompetencyID'),
            'intIndicatorID'  => $this->request->getPost('intIndicatorID'),
            'bitResult'       => $this->request->getPost('bitResult'),
            'txtUpdatedBy'    => session()->get('username'),
            'dtmUpdatedDate'  => date('Y-m-d H:i:s'),
        ];

        if ($this->assessmentModel->update($id, $data)) {
            return redirect()->to('/assessment')->with('success', 'Assessment updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update assessment.');
    }

    // Delete Assessment
    public function delete($id = null)
    {
        $assessment = $this->assessmentModel->find($id);

        if ($assessment && $this->assessmentModel->delete($id)) {
            // Update Progress
            $this->updateProgress($assessment['intUserID'], $assessment['intCompetencyID']);

            return $this->respondDeleted(['status' => 'success', 'message' => 'Assessment deleted.']);
        }

        return $this->fail(['status' => 'error', 'message' => 'Failed to delete assessment.']);
    }

    // Private Method to Update Competency Progress
    private function updateProgress($intUserID, $intCompetencyID)
    {
        $totalIndicators = $this->assessmentModel
            ->where('intCompetencyID', $intCompetencyID)
            ->countAllResults();

        if ($totalIndicators === 0) return;

        $passedIndicators = $this->assessmentModel
            ->where('intCompetencyID', $intCompetencyID)
            ->where('bitResult', 1)
            ->countAllResults();

        $progress = ($passedIndicators / $totalIndicators) * 100;

        $progressData = [
            'fltProgress'     => $progress,
            'dtmLastUpdated'  => date('Y-m-d H:i:s'),
            'txtGUID'         => uniqid()
        ];

        $this->progressModel->updateOrInsert([
            'intUserID'       => $intUserID,
            'intCompetencyID' => $intCompetencyID
        ], $progressData);
    }
}
