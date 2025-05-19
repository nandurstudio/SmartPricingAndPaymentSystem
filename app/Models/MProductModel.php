<?php

namespace App\Models;

use CodeIgniter\Model;

class MProductModel extends Model
{
    protected $table            = 'm_product';
    protected $primaryKey       = 'intProductID';
    protected $allowedFields    = [
        'txtProductName',
        'txtProductDescription',
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
        'txtProductName' => 'required|max_length[255]',
    ];
}