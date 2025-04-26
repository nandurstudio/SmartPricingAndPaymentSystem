<?php

namespace App\Models;

use CodeIgniter\Model;

class CompetencyModel extends Model
{
    protected $table = 'mCompetency'; // Nama tabel
    protected $primaryKey = ['intCompetencyID', 'intJobTitleID']; // Composite key

    protected $allowedFields = [
        'intCompetencyID',
        'intJobTitleID',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];

    public function getCompetenciesByJobTitle($jobTitleId)
    {
        // Menggunakan query builder
        $builder = $this->db->table('mCompetency mc');
        $builder->select('mc.intCompetencyID, mc.intJobTitleID, c.txtCompetency');
        $builder->join('mCompetencies c', 'mc.intCompetencyID = c.intCompetencyID');
        $builder->where('mc.intJobTitleID', $jobTitleId);

        $competencies = $builder->get()->getResultArray();

        return $competencies; // Mengembalikan data sebagai array
    }

    // Mendapatkan detail competency berdasarkan ID
    public function getCompetency($competencyId, $jobTitleId)
    {
        return $this->where([
            'intCompetencyID' => $competencyId,
            'intJobTitleID' => $jobTitleId
        ])->first();
    }
    // File: app/Models/CompetencyModel.php

    public function getCompetencies($start, $length, $searchValue)
    {
        $this->select('*');

        if ($searchValue) {
            $this->groupStart()
                ->like('intCompetencyID', $searchValue) // Pencarian di CompetencyID
                ->orLike('intJobTitleID', $searchValue) // Pencarian di JobTitleID
                ->groupEnd();
        }

        return $this->findAll($length, $start);
    }

    public function countAllCompetencies($searchValue)
    {
        if ($searchValue) {
            $this->groupStart()
                ->like('intCompetencyID', $searchValue) // Pencarian di CompetencyID
                ->orLike('intJobTitleID', $searchValue) // Pencarian di JobTitleID
                ->groupEnd();
        }

        return $this->countAll();
    }
}
