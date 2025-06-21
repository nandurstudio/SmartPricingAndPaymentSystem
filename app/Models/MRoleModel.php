<?php

namespace App\Models;

use CodeIgniter\Model;

class MRoleModel extends Model
{
    protected $table = 'm_role'; // Table name
    protected $primaryKey = 'intRoleID'; // Primary key
    protected $allowedFields = [
        'txtGUID',
        'txtRoleName',
        'txtRoleDesc',
        'txtRoleNote',
        'bitActive',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];

    // Timestamps configuration
    protected $useTimestamps = true;
    protected $createdField = 'dtmCreatedDate';
    protected $updatedField = 'dtmUpdatedDate';

    // Create Operation
    public function createRole($data)
    {
        return $this->insert($data);
    }

    // Read Operation
    public function getRole($id)
    {
        return $this->find($id);
    }

    // Get All Roles
    public function getAllRoles()
    {
        return $this->findAll();
    }

    // Update Operation
    public function updateRole($id, $data)
    {
        return $this->update($id, $data);
    }

    // Delete Operation
    public function deleteRole($id)
    {
        return $this->delete($id);
    }
}
