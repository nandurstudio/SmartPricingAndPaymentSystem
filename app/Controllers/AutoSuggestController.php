<?php

namespace App\Controllers;

use App\Models\AutoSuggestModel;
use CodeIgniter\Controller;

class AutoSuggestController extends Controller
{
    protected $autoSuggestModel;

    public function __construct()
    {
        $this->autoSuggestModel = new AutoSuggestModel();
    }

    public function index()
    {
        return view('auto_suggest/index'); // Adjust this path based on your views
    }

    public function search()
    {
        $searchTerm = $this->request->getPost('searchTerm');

        // Pastikan input aman
        $searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');

        // Mengambil data beserta statistik dari model
        $results = $this->autoSuggestModel->getSuggestionsWithStatistics($searchTerm);

        // Return JSON response
        return $this->response->setJSON($results ?: []);
    }

    public function jobTitleIndicators()
    {
        $results = $this->autoSuggestModel->getUserJobTitleIndicators();

        // Return the result as a view or JSON based on your needs
        return $this->response->setJSON($results);
    }
}
