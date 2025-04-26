<?php

namespace App\Models;

use CodeIgniter\Model;

class LineModel extends Model
{
    protected $table = 'mLine';
    protected $primaryKey = 'intLineID';
    protected $allowedFields = ['txtLine', 'txtDesc', 'bitActive', 'txtInsertedBy', 'dtmInsertedDate', 'txtUpdatedBy', 'dtmUpdatedDate', 'txtGUID'];

    protected $useTimestamps = true;
    protected $createdField  = 'dtmInsertedDate';
    protected $updatedField  = 'dtmUpdatedDate';
}
