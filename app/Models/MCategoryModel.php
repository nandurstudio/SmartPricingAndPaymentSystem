<?php

namespace App\Models;

use CodeIgniter\Model;

class MCategoryModel extends Model
{
    protected $table            = 'm_category';
    protected $primaryKey       = 'intCategoryID';
    protected $allowedFields    = [
        'txtCategoryName',
        'bitActive',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtLastUpdatedBy',
        'dtmLastUpdatedDate',
        'txtGUID'
    ];
    protected $useTimestamps    = false; // Karena field timestamp diatur otomatis oleh MySQL

    // Optional: jika ingin validasi
    protected $validationRules = [
        'txtCategoryName' => 'required|max_length[100]',
    ];
}