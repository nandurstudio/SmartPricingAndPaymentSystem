<?php

namespace App\Models;

use CodeIgniter\Model;

class UserJobTitleModel extends Model
{
    protected $table            = 'trUser_JobTitle'; // Tabel asli di database
    protected $primaryKey       = 'intTrUserJobTitleID';
    protected $allowedFields    = [
        'intUserID',
        'intJobTitleID',
        'bitAchieved',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID',
        'intLineID' // Menambahkan intLineID ke allowedFields
    ];

    public function getUserJobTitles($start, $length, $searchValue, $orderBy, $orderDirection)
    {
        $builder = $this->db->table('trUser_JobTitle')
        ->select('trUser_JobTitle.*, mUser.txtUserName, mUser.txtFullName, mJobTitle.txtJobTitle, mLine.txtLine') // Tambahkan mLine.txtLine
        ->join('mUser', 'mUser.intUserID = trUser_JobTitle.intUserID', 'left')
        ->join('mJobTitle', 'mJobTitle.intJobTitleID = trUser_JobTitle.intJobTitleID', 'left')
        ->join('mLine', 'mLine.intLineID = trUser_JobTitle.intLineID', 'left'); // Join dengan mLine

        // Tambahkan pencarian jika diperlukan
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('mUser.txtUserName', $searchValue)
                ->orLike('mUser.txtFullName', $searchValue)
                ->orLike('mJobTitle.txtJobTitle', $searchValue)
                ->orLike('trUser_JobTitle.intUserID', $searchValue)
                ->orLike('trUser_JobTitle.bitActive', $searchValue)
                ->orLike('mLine.txtLine', $searchValue) // Menambahkan pencarian untuk mLine.txtLine
                ->groupEnd();
        }

        // Tambahkan sorting
        $builder->orderBy($orderBy, $orderDirection);

        // Batasi jumlah data yang dikembalikan
        $builder->limit($length, $start);

        // Eksekusi query dan kembalikan hasilnya
        return $builder->get()->getResultArray();
    }

    public function getUserJobTitlesAutoSuggest($start, $length, $searchValue = '', $searchName = '', $searchJobTitle = '', $searchDepartment = '', $searchLine = '', $orderBy = 'intTrUserJobTitleID', $orderDirection = 'asc')
    {
        $builder = $this->db->table('trUser_JobTitle')
        ->select('
            trUser_JobTitle.*, 
            trUser_JobTitle.bitAchieved, 
            mUser.txtFullName, 
            mUser.txtNick, 
            mUser.txtUserName, 
            mUser.txtEmpID, 
            mUser.txtEmail, 
            mUser.txtPhoto, 
            mUser.intRoleID, 
            mUser.dtmJoinDate,
            mJobTitle.txtJobTitle, 
            mDepartment.txtDepartmentName, 
            mLine.txtLine,
            supervisor.txtFullName as supervisorName
        ')
        ->join('mUser', 'mUser.intUserID = trUser_JobTitle.intUserID', 'left')
            ->join('mUser supervisor', 'supervisor.intUserID = mUser.intSupervisorID', 'left')
            ->join('mJobTitle', 'mJobTitle.intJobTitleID = trUser_JobTitle.intJobTitleID', 'left')
            ->join('mDepartment', 'mDepartment.intDepartmentID = mUser.intDepartmentID', 'left')
            ->join('mLine', 'mLine.intLineID = trUser_JobTitle.intLineID', 'left'); // Join dengan mLine

        // Filter tambahan untuk pencarian eksak
        if (!empty($searchName)) {
            $builder->like('mUser.txtFullName', $searchName);
        }
        if (!empty($searchJobTitle)) {
            $builder->where('mJobTitle.txtJobTitle', $searchJobTitle);
        }
        if (!empty($searchDepartment)) {
            $builder->where('mDepartment.txtDepartmentName', $searchDepartment);
        }
        if (!empty($searchLine)) {
            $builder->where('mLine.txtLine', $searchLine); // Menambahkan pencarian berdasarkan mLine
        }

        $builder->orderBy($orderBy, $orderDirection)
            ->limit($length, $start);

        return $builder->get()->getResultArray();
    }

    public function countAllUserJobTitles($searchValue = null)
    {
        $builder = $this->table($this->table);

        if ($searchValue) {
            $builder->like('intUserID', $searchValue)
                ->orLike('intJobTitleID', $searchValue); // Ganti dengan kolom yang ingin dihitung
        }

        return $builder->countAllResults();
    }

    public function getAllUserJobTitles()
    {
        return $this->findAll(); // Mengambil semua data dari mCompetencies
    }

    public function countFilteredAutoSuggest($searchValue, $searchName, $searchJobTitle, $searchDepartment, $searchLine)
    {
        $builder = $this->db->table('trUser_JobTitle')
            ->join('mUser', 'mUser.intUserID = trUser_JobTitle.intUserID', 'left')
            ->join('mJobTitle', 'mJobTitle.intJobTitleID = trUser_JobTitle.intJobTitleID', 'left')
            ->join('mDepartment', 'mDepartment.intDepartmentID = mUser.intDepartmentID', 'left')
            ->join('mLine', 'mLine.intLineID = mUser.intLineID', 'left');

        // Filter tambahan untuk pencarian eksak
        if (!empty($searchName)) {
            $builder->where('mUser.txtFullName', $searchName); // Pencarian eksak
        }
        if (!empty($searchJobTitle)) {
            $builder->where('mJobTitle.txtJobTitle', $searchJobTitle); // Pencarian eksak
        }
        if (!empty($searchDepartment)) {
            $builder->where('mDepartment.txtDepartmentName', $searchDepartment); // Pencarian eksak
        }
        if (!empty($searchLine)) {
            $builder->where('mLine.txtLine', $searchLine); // Pencarian eksak
        }

        return $builder->countAllResults();
    }
}
