<?php

namespace App\Models;

use CodeIgniter\Model;

class CompetenciesModel extends Model
{
    protected $table      = 'mCompetencies';
    protected $primaryKey = 'intCompetencyID';

    protected $allowedFields = [
        'txtCompetency',
        'txtDefinition',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];

    public function getCompetencies($start, $length, $searchValue, $orderBy, $orderDirection)
    {
        // Pastikan builder dideklarasikan dengan benar
        $builder = $this->db->table('mcompetencies');

        // Tambahkan pencarian jika diperlukan
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('txtCompetency', $searchValue)
                ->orLike('intCompetencyID', $searchValue) // Tambahkan pencarian berdasarkan ID
                ->groupEnd();
        }

        // Tambahkan sorting
        $builder->orderBy($orderBy, $orderDirection);

        // Batasi jumlah data yang dikembalikan
        $builder->limit($length, $start);

        // Eksekusi query dan kembalikan hasilnya
        return $builder->get()->getResultArray();
    }

    public function countAllCompetencies($searchValue = null)
    {
        $builder = $this->table($this->table);

        if ($searchValue) {
            $builder->like('txtCompetency', $searchValue)
                ->orLike('intCompetencyID', $searchValue); // Ganti dengan kolom yang ingin dihitung
        }

        return $builder->countAllResults();
    }

    public function getAllCompetencies()
    {
        return $this->findAll(); // Mengambil semua data dari mCompetencies
    }
}
