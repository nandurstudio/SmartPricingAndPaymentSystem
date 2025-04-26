<?php

namespace App\Controllers;

use App\Models\IndicatorModel;
use App\Models\UserJobTitleCompetencyIndicatorModel;

class IndicatorController extends BaseController
{
    protected $indicatorModel;
    protected $userJobTitleCompetencyIndicatorModel;

    public function __construct()
    {
        $this->indicatorModel = new IndicatorModel();
        $this->userJobTitleCompetencyIndicatorModel = new UserJobTitleCompetencyIndicatorModel(); // Tambahkan ini
    }

    public function getIndicatorsByCompetency()
    {
        $competencyID = $this->request->getPost('competencyID');
        $userID = $this->request->getPost('userID'); // Ambil userID dari permintaan

        // Ambil indikator berdasarkan competency dan user
        $indicators = $this->userJobTitleCompetencyIndicatorModel->getAllIndicatorsByCompetency($competencyID, $userID);

        return $this->response->setJSON($indicators);
    }
}
