<?php

namespace App\Models;

use CodeIgniter\Model;

class IndicatorModel extends Model
{
    protected $table = 'mIndicators'; // Nama tabel
    protected $primaryKey = 'intIndicatorID'; // Primary key
    protected $allowedFields = [
        'intCompetencyID',
        'txtIndicator',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];

    // Menambahkan fungsi untuk mendapatkan semua indikator berdasarkan ID kompetensi
    public function getIndicatorsByCompetency($competencyID)
    {
        return $this->where('intCompetencyID', $competencyID)
            ->where('bitActive', 1) // Hanya ambil indikator yang aktif
            ->findAll();
    }

    public function getIndicatorsByCompetencyUser()
    {
        $competencyID = $this->request->getPost('competencyID');
        $userID = $this->request->getPost('userID'); // Pastikan userID juga diterima dari request

        // Panggil model untuk mendapatkan indikator berdasarkan competency dan user
        $indicators = $this->indicatorModel->getAllIndicatorsByCompetency($competencyID, $userID);

        return $this->response->setJSON($indicators);
    }


    // Menambahkan fungsi untuk mendapatkan indikator berdasarkan ID
    public function getIndicator($id)
    {
        return $this->find($id);
    }

    // Menambahkan fungsi untuk menyimpan indikator baru
    public function createIndicator($data)
    {
        $data['txtGUID'] = uniqid(); // Generate GUID
        return $this->insert($data);
    }

    // Menambahkan fungsi untuk memperbarui indikator
    public function updateIndicator($id, $data)
    {
        return $this->update($id, $data);
    }

    // Menambahkan fungsi untuk menghapus indikator
    public function deleteIndicator($id)
    {
        return $this->delete($id);
    }

    // Menambahkan fungsi untuk mendapatkan semua kompetensi
    public function findAllCompetencies()
    {
        // Menggunakan query untuk mendapatkan semua kompetensi dari tabel mCompetencies
        return $this->db->table('mCompetencies')->get()->getResultArray();
    }
}
