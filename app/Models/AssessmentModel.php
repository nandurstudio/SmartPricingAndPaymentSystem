<?php

namespace App\Models;

use CodeIgniter\Model;

class AssessmentModel extends Model
{
    protected $table = 'trAssessment';
    protected $primaryKey = 'intAssessmentID';
    protected $allowedFields = [
        'intUserID',
        'intLineID',
        'intJobTitleID',
        'intCompetencyID',
        'intIndicatorID',
        'bitResult',
        'dtmAssessedDate',
        'txtAssessedBy',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];
}
