<?php

namespace App\Controllers;

use App\Models\IndicatorModel;
use App\Models\CompetencyModel;
use App\Models\CompetencyProgressModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class CompetencyProgressController extends Controller
{
    protected $competencyModel;
    protected $indicatorModel;
    protected $userModel;
    protected $competencyProgressModel;

    public function __construct()
    {
        $this->competencyModel = new CompetencyModel();
        $this->indicatorModel = new IndicatorModel();
        $this->userModel = new UserModel();
        $this->competencyProgressModel = new CompetencyProgressModel();
    }

    // Menampilkan daftar progress competency
    public function index()
    {
        // Ambil semua data progress competency
        $competencyProgress = $this->competencyProgressModel->findAll();

        // Pastikan variabel $competencyProgress didefinisikan dan diteruskan ke view
        return view('competency_progress/index', [
            'competencyProgress' => $competencyProgress
        ]);
    }

    public function create()
    {
        $userId = session()->get('user_id'); // Ambil ID user dari sesi
        $competencyId = $this->request->getGet('competency_id'); // Ambil ID kompetensi dari query string

        // Ambil indikator untuk kompetensi
        $indicators = $this->indicatorModel->getIndicatorsByCompetency($competencyId);

        // Ambil progress yang ada untuk pengguna dan kompetensi
        $progress = $this->competencyProgressModel->getProgressByUserAndCompetency($userId, $competencyId);
        $selectedIndicators = [];
        if ($progress) {
            $selectedIndicators = json_decode($progress['indicators'], true);
        }

        // Ambil daftar kompetensi
        $competencies = $this->competencyModel->findAll(); // Pastikan model ini diinisialisasi

        // Ambil daftar pengguna
        $users = $this->userModel->findAll(); // Pastikan model ini diinisialisasi

        return view('competency_progress/create', [
            'indicators' => $indicators,
            'selectedIndicators' => $selectedIndicators,
            'competencies' => $competencies,
            'users' => $users,
        ]);
    }

    public function getIndicators($competencyID, $userID)
    {
        // Ambil indikator berdasarkan ID kompetensi
        $indicators = $this->indicatorModel->getIndicatorsByCompetency($competencyID);

        if (empty($indicators)) {
            return json_encode(['message' => 'Tidak ada indikator untuk kompetensi ini.']);
        }

        // Ambil kemajuan pengguna untuk kompetensi ini
        $progress = $this->competencyProgressModel->getProgressByUserAndCompetency($userID, $competencyID);

        // Menyiapkan response
        $response = [];
        foreach ($indicators as $indicator) {
            $response[] = [
                'intIndicatorID' => $indicator['intIndicatorID'],
                'txtIndicator' => $indicator['txtIndicator'],
                'bitAchieved' => isset($progress) && $progress['intAchievedIndicators'] > 0 ? 1 : 0 // Ganti logika sesuai kebutuhan
            ];
        }

        return $this->response->setJSON($response);
    }

    // Menyimpan progress baru
    public function store()
    {
        $data = $this->request->getPost();
        $indicators = $data['indicators'] ?? []; // Ambil semua indikator yang dipilih

        // Siapkan array untuk menyimpan status indikator
        $indicatorValues = [];

        foreach ($indicators as $indicatorID => $value) {
            $indicatorValues[$indicatorID] = (int)$value; // Simpan status indikator
        }

        // Hitung jumlah indikator yang dicapai
        $achievedIndicatorsCount = count(array_filter($indicatorValues, fn($v) => $v === 1));

        // Simpan progress user
        $progressData = [
            'intUserID' => $data['intUserID'], // ID User
            'intCompetencyID' => $data['intCompetencyID'], // ID Kompetensi
            'intAchievedIndicators' => $achievedIndicatorsCount, // Hitung indikator yang dicapai
            'dtmProgressDate' => date('Y-m-d H:i:s'), // Tanggal progress
            'txtInsertedBy' => 'system', // Nama pengguna yang menyimpan data
            'indicators' => json_encode($indicatorValues), // Simpan status indikator dalam format JSON
        ];

        // Simpan progress
        if ($this->competencyProgressModel->insert($progressData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil disimpan.']);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Gagal menyimpan data.']);
        }
    }

    // Menampilkan form untuk mengedit progress
    public function edit($id)
    {
        $data['progress'] = $this->competencyProgressModel->find($id);
        return view('competency_progress/edit', $data);
    }

    // Mengupdate progress yang ada
    public function update($id)
    {
        $data = [
            'intUserID' => $this->request->getPost('intUserID'),
            'intCompetencyID' => $this->request->getPost('intCompetencyID'),
            'intAchievedIndicators' => $this->request->getPost('intAchievedIndicators'),
            'intTotalIndicators' => $this->request->getPost('intTotalIndicators'),
            'txtUpdatedBy' => 'system' // Ganti sesuai kebutuhan
        ];

        $this->competencyProgressModel->update($id, $data);
        return redirect()->to('/competencies')->with('success', 'Progress berhasil diperbarui.');
    }

    // Menampilkan detail progress
    public function view($id)
    {
        $data['progress'] = $this->competencyProgressModel->find($id);
        return view('competency_progress/view', $data);
    }

    // Menghapus progress
    public function delete($id)
    {
        $this->competencyProgressModel->delete($id);
        return redirect()->to('/competencies')->with('success', 'Progress berhasil dihapus.');
    }
}
