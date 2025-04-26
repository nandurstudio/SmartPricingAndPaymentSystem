<?php

namespace App\Models;

use CodeIgniter\Model;

class ParameterModel extends Model
{
    protected $table = 'mParameter';
    protected $primaryKey = 'intParameterID';
    protected $allowedFields = [
        'txtParameterName',
        'txtParameterDesc',
        'bitActive',
        'txtInsertedBy',
        'txtUpdatedBy',
        'txtGUID',
    ];

    // Optional: Untuk timestamps otomatis
    protected $useTimestamps = true;
    protected $createdField = 'dtmInsertedDate';
    protected $updatedField = 'dtmUpdatedDate';
}
