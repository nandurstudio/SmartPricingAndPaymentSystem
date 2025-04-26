<?php

namespace App\Models;

use CodeIgniter\Model;

class JobTitleModel extends Model
{
    protected $table = 'mJobTitle';
    protected $primaryKey = 'intJobTitleID';
    protected $allowedFields = [
        'txtJobTitle',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'dtmInsertedDate';
    protected $updatedField  = 'dtmUpdatedDate';
}
