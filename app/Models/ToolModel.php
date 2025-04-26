<?php

namespace App\Models;

use CodeIgniter\Model;

class ToolModel extends Model
{
    protected $table = 'mTool';
    protected $primaryKey = 'intToolID';
    protected $allowedFields = [
        'txtToolName',
        'txtToolDesc',
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
