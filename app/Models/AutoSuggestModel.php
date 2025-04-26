<?php

namespace App\Models;

use CodeIgniter\Model;

class AutoSuggestModel extends Model
{
    protected $table = 'mUser';
    protected $allowedFields = [
        'txtUserName',
        'txtFullName',
        'txtNick',
        'txtEmpID',
        'txtEmail',
        'txtPassword',
        'bitActive',
        'intRoleID',
        'dtmLastLogin',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID',
        'intSupervisorID',
        'intLineID',
        'intJobTitleID',
        'intDepartmentID',
        'txtPhoto'
    ];

    public function getSuggestionsWithStatistics($searchTerm)
    {
        return $this->select('
                mUser.intUserID,
                c.intCompetencyID,
                mUser.txtFullName, 
                jt.txtJobTitle, 
                d.txtDepartmentName, 
                l.txtLine, 
                c.txtCompetency, 
                i.txtIndicator,
                t.bitAchieved,
                mUser.txtPhoto,
                supervisor.txtFullName as supervisorName
            ')
            ->join('trUserJobTitleCompetencyIndicator t', 't.intUserID = mUser.intUserID')
            ->join('mJobTitle jt', 'jt.intJobTitleID = t.intJobTitleID')
            ->join('mDepartment d', 'd.intDepartmentID = mUser.intDepartmentID')
            ->join('mLine l', 'l.intLineID = mUser.intLineID')
            ->join('mCompetencies c', 'c.intCompetencyID = t.intCompetencyID')
            ->join('mIndicators i', 'i.intIndicatorID = t.intIndicatorID')
            ->join('mUser supervisor', 'supervisor.intUserID = mUser.intSupervisorID', 'left') // Join untuk mendapatkan nama supervisor
            ->where('mUser.bitActive', 1)
            ->groupBy('c.intCompetencyID, mUser.intUserID, jt.txtJobTitle, d.txtDepartmentName, l.txtLine, c.txtCompetency, i.txtIndicator, t.bitAchieved, mUser.txtPhoto, supervisor.txtFullName')
            ->like('mUser.txtFullName', $searchTerm)
            ->orLike('jt.txtJobTitle', $searchTerm)
            ->orLike('l.txtLine', $searchTerm)
            ->orLike('d.txtDepartmentName', $searchTerm)
            ->orLike('c.txtCompetency', $searchTerm)
            ->orLike('i.txtIndicator', $searchTerm)
            ->findAll();
    }
}
