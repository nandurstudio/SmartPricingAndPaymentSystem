<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    // Define the table associated with the model
    protected $table      = 'mDepartment';

    // Define the primary key for the table
    protected $primaryKey = 'intDepartmentID';

    // Define the allowed fields that can be inserted or updated
    protected $allowedFields = [
        'txtDepartmentCode',
        'txtDepartmentName',
        'txtDepartmentShortName',
        'bitActive',
        'txtInsertedBy',
        'dtmInsertedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID'
    ];

    // Enable timestamps
    protected $useTimestamps = true;

    // Specify custom date format (optional, based on your table's configuration)
    protected $dateFormat = 'datetime';

    // Set the created at and updated at field names (optional, since we are using default)
    protected $createdField  = 'dtmInsertedDate';
    protected $updatedField  = 'dtmUpdatedDate';

    // Validation rules for fields (optional, but recommended for data integrity)
    protected $validationRules = [
        'txtDepartmentCode'      => 'required|is_unique[mDepartment.txtDepartmentCode]',
        'txtDepartmentName'      => 'required',
        'txtGUID'                => 'required|is_unique[mDepartment.txtGUID]',
        'bitActive'              => 'permit_empty|in_list[0,1]',
        'txtInsertedBy'          => 'permit_empty',
        'txtUpdatedBy'           => 'permit_empty',
    ];

    // Custom validation messages (optional)
    protected $validationMessages = [
        'txtDepartmentCode' => [
            'is_unique' => 'The department code must be unique.'
        ],
        'txtGUID' => [
            'is_unique' => 'The GUID must be unique.'
        ]
    ];

    // Enable soft deletes (optional)
    protected $useSoftDeletes = false;

    // Define the return type as array (optional, depending on how you want results)
    protected $returnType     = 'array';

    // Automatically escape input (this helps protect from SQL injection)
    protected $escape = true;

    // Optionally, you can define functions for custom queries if needed
    public function getActiveDepartments()
    {
        return $this->where('bitActive', 1)->findAll();
    }

    public function getDepartmentByCode($code)
    {
        return $this->where('txtDepartmentCode', $code)->first();
    }
}
