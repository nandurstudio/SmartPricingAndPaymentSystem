<?php

namespace App\Models\Traits;

trait AuditTrait
{
    protected function beforeInsert(array $data)
    {
        // Add GUID if not set
        if (!isset($data['data']['txtGUID']) && !isset($data['data']['guid'])) {
            $guidField = isset($this->guidField) ? $this->guidField : 'txtGUID';
            $data['data'][$guidField] = $this->generateGuid();
        }

        // Add created_date and created_by
        if (!isset($data['data']['created_date']) && !isset($data['data']['dtmCreatedDate'])) {
            $createdDateField = isset($this->createdDateField) ? $this->createdDateField : 
                (in_array('dtmCreatedDate', $this->allowedFields) ? 'dtmCreatedDate' : 'created_date');
            $data['data'][$createdDateField] = date('Y-m-d H:i:s');
        }

        if (!isset($data['data']['created_by']) && !isset($data['data']['txtCreatedBy'])) {
            $createdByField = isset($this->createdByField) ? $this->createdByField : 
                (in_array('txtCreatedBy', $this->allowedFields) ? 'txtCreatedBy' : 'created_by');
            $data['data'][$createdByField] = session()->get('userName') ?? 'system';
        }

        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        // Add updated_date and updated_by
        if (!isset($data['data']['updated_date']) && !isset($data['data']['dtmLastUpdatedDate'])) {
            $updatedDateField = isset($this->updatedDateField) ? $this->updatedDateField : 
                (in_array('dtmLastUpdatedDate', $this->allowedFields) ? 'dtmLastUpdatedDate' : 'updated_date');
            $data['data'][$updatedDateField] = date('Y-m-d H:i:s');
        }

        if (!isset($data['data']['updated_by']) && !isset($data['data']['txtLastUpdatedBy'])) {
            $updatedByField = isset($this->updatedByField) ? $this->updatedByField : 
                (in_array('txtLastUpdatedBy', $this->allowedFields) ? 'txtLastUpdatedBy' : 'updated_by');
            $data['data'][$updatedByField] = session()->get('userName') ?? 'system';
        }

        return $data;
    }

    private function generateGuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
