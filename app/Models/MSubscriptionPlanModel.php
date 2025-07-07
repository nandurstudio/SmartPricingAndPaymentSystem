<?php
namespace App\Models;

use CodeIgniter\Model;

class MSubscriptionPlanModel extends Model
{
    protected $table = 'm_subscription_plans';
    protected $primaryKey = 'intPlanID';
    protected $allowedFields = [
        'txtGUID', 'txtName', 'txtCode', 'decAmount', 'intDuration', 'jsonFeatures', 'txtDescription', 'bitActive', 'txtCreatedBy', 'dtmCreatedDate', 'txtUpdatedBy', 'dtmUpdatedDate'
    ];

    public function getPlanByCode($code)
    {
        return $this->where('txtCode', $code)->where('bitActive', 1)->first();
    }

    public function getAllActivePlans()
    {
        return $this->where('bitActive', 1)->orderBy('decAmount', 'ASC')->findAll();
    }
}
