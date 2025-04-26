<?php

namespace App\Models;

use CodeIgniter\Model;

class CompetencyProgressModel extends Model
{
    protected $table = 'trCompetencyProgress';
    protected $primaryKey = 'intProgressID';
    protected $allowedFields = [
        'intUserID',
        'intCompetencyID',
        'fltProgress',
        'dtmLastUpdated',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];

    protected $useTimestamps = true;  // Menggunakan timestamp otomatis
    protected $createdField = 'dtmInsertedDate';  // Nama field untuk tanggal dibuat
    protected $updatedField = 'dtmUpdatedDate';    // Nama field untuk tanggal diperbarui

    // Menambahkan metode untuk mendapatkan progress berdasarkan User ID dan Competency ID
    public function getProgressByUserAndCompetency($userId, $competencyId)
    {
        return $this->where('intUserID', $userId)
            ->where('intCompetencyID', $competencyId)
            ->first();
    }

    // Menyimpan progress baru
    public function createProgress($data)
    {
        $data['txtGUID'] = uniqid(); // Generate GUID
        return $this->insert($data);
    }

    // Mengupdate progress yang sudah ada
    public function updateProgress($id, $data)
    {
        return $this->update($id, $data);
    }

    // Menghapus progress
    public function deleteProgress($id)
    {
        return $this->delete($id);
    }

    // Menyimpan nilai indikator untuk pengguna
    public function saveIndicatorsProgress($userID, $jobTitleID, $competencyID, $indicators)
    {
        foreach ($indicators as $indicatorID => $achievedStatus) {
            // Siapkan data untuk disimpan
            $data = [
                'intUserID' => $userID,
                'intJobTitleID' => $jobTitleID,
                'intCompetencyID' => $competencyID,
                'intIndicatorID' => $indicatorID,
                'bitAchieved' => $achievedStatus, // 1 jika dicapai, 0 jika tidak
                'txtGUID' => uniqid(),
                'txtInsertedBy' => 'system',
                'dtmInsertedDate' => date('Y-m-d H:i:s'),
            ];

            // Cek apakah pencapaian indikator sudah ada
            $existing = $this->db->table('trUserJobTitleCompetencyIndicator')
                ->where([
                    'intUserID' => $userID,
                    'intJobTitleID' => $jobTitleID,
                    'intCompetencyID' => $competencyID,
                    'intIndicatorID' => $indicatorID
                ])->get()->getRowArray();

            if ($existing) {
                // Jika ada, update data
                $this->db->table('trUserJobTitleCompetencyIndicator')
                    ->where('intUserJobTitleCompetencyIndicatorID', $existing['intUserJobTitleCompetencyIndicatorID'])
                    ->update(['bitAchieved' => $achievedStatus, 'txtUpdatedBy' => 'system', 'dtmUpdatedDate' => date('Y-m-d H:i:s')]);
            } else {
                // Jika tidak ada, buat data baru
                $this->db->table('trUserJobTitleCompetencyIndicator')->insert($data);
            }
        }
        return true;
    }
}
