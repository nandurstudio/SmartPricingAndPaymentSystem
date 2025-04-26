<?php

namespace App\Models;

use CodeIgniter\Model;

class FunctionalCompetencyModel extends Model
{
    protected $table = 'mFunctionalCompetency';
    protected $primaryKey = 'intFunctionalCompetencyID';
    protected $allowedFields = [
        'intJobTitleID',
        'intCompetencyID',
        'intToolID',
        'intLineID',
        'intIndicatorID',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];

    /**
     * Mengambil detail relasi untuk mFunctionalCompetency.
     */
    public function getDetails($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            mFunctionalCompetency.*, 
            mJobTitle.txtJobTitle, 
            mCompetencies.txtCompetency, 
            mCompetencies.txtDefinition, 
            mTool.txtToolName, 
            mTool.txtToolDesc, 
            mLine.txtLine, 
            mLine.txtDesc, 
            mIndicators.txtIndicator
        ');
        $builder->join('mJobTitle', 'mFunctionalCompetency.intJobTitleID = mJobTitle.intJobTitleID', 'left');
        $builder->join('mCompetencies', 'mFunctionalCompetency.intCompetencyID = mCompetencies.intCompetencyID', 'left');
        $builder->join('mTool', 'mFunctionalCompetency.intToolID = mTool.intToolID', 'left');
        $builder->join('mLine', 'mFunctionalCompetency.intLineID = mLine.intLineID', 'left');
        $builder->join('mIndicators', 'mFunctionalCompetency.intIndicatorID = mIndicators.intIndicatorID', 'left');

        if ($id) {
            $builder->where('mFunctionalCompetency.intFunctionalCompetencyID', $id);
        }

        return $id ? $builder->get()->getRowArray() : $builder->get()->getResultArray();
    }
}
