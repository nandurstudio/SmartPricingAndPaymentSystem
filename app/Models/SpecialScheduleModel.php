<?php

namespace App\Models;

use CodeIgniter\Model;

class SpecialScheduleModel extends Model
{
    protected $table = 'm_special_schedules';
    protected $primaryKey = 'intSpecialScheduleID';
    protected $allowedFields = [
        'intServiceID',
        'dtmSpecialDate',
        'dtmStartTime',
        'dtmEndTime',
        'bitIsClosed',
        'intSlotDuration',
        'txtNote',
        'txtGUID',
        'bitActive',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate',
    ];
    public $timestamps = false;
}
