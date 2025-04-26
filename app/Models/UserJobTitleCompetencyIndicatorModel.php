<?php

namespace App\Models;

use CodeIgniter\Model;

class UserJobTitleCompetencyIndicatorModel extends Model
{
    protected $table            = 'trUserJobTitleCompetencyIndicator';
    protected $primaryKey       = 'intUserJobTitleCompetencyIndicatorID';
    protected $allowedFields    = [
        'intUserID',
        'intJobTitleID',
        'intCompetencyID',
        'intIndicatorID',
        'bitAchieved',
        'bitActive',
        'txtGUID',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'dtmInsertedDate';
    protected $updatedField     = 'dtmUpdatedDate';

    public function getIndicatorData($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }
        return $this->find($id);
    }

    public function getIndicatorsWithDetails()
    {
        $builder = $this->db->table($this->table);
        $builder->select('trUserJobTitleCompetencyIndicator.*, mUser.txtFullName, mJobTitle.txtJobTitle, mCompetencies.txtCompetency, mIndicators.txtIndicator');
        $builder->join('mUser', 'mUser.intUserID = trUserJobTitleCompetencyIndicator.intUserID');
        $builder->join('mJobTitle', 'mJobTitle.intJobTitleID = trUserJobTitleCompetencyIndicator.intJobTitleID');
        $builder->join('mCompetency', 'mCompetency.intCompetencyID = trUserJobTitleCompetencyIndicator.intCompetencyID');
        $builder->join('mCompetencies', 'mCompetencies.intCompetencyID = mCompetency.intCompetencyID'); // Update join
        $builder->join('mIndicators', 'mIndicators.intIndicatorID = trUserJobTitleCompetencyIndicator.intIndicatorID');

        return $builder->get()->getResultArray();
    }

    public function getAllIndicatorsByCompetency($competencyID, $userID)
    {
        $builder = $this->db->table('mIndicators');
        $builder->select('mIndicators.txtIndicator, trUserJobTitleCompetencyIndicator.bitAchieved');
        $builder->join(
            'trUserJobTitleCompetencyIndicator',
            'trUserJobTitleCompetencyIndicator.intIndicatorID = mIndicators.intIndicatorID 
                    AND trUserJobTitleCompetencyIndicator.intUserID = ' . $userID,
            'left'
        );
        $builder->where('mIndicators.intCompetencyID', $competencyID);
        return $builder->get()->getResultArray();
    }
}
