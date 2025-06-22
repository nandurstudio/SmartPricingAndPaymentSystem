<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'm_services';
    protected $primaryKey = 'intServiceID';
    protected $allowedFields = [
        'intTenantID',
        'intServiceTypeID', 
        'txtGUID',
        'txtName',
        'txtDescription',
        'decPrice',
        'intDuration',
        'intCapacity',
        'txtImage',
        'bitActive',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'dtmCreatedDate';
    protected $updatedField = 'dtmUpdatedDate';

    // Override insert to add logging
    public function insert($data = null, bool $returnID = true)
    {
        // Log the insert attempt
        log_message('debug', 'ServiceModel: Attempting to insert with data: ' . json_encode($data));
        
        try {
            $result = parent::insert($data, $returnID);
            log_message('debug', 'ServiceModel: Insert result: ' . json_encode($result));
            
            if (!$result) {
                log_message('error', 'ServiceModel: Insert failed. DB Error: ' . json_encode($this->db->error()));
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'ServiceModel: Exception during insert: ' . $e->getMessage());
            throw $e;
        }
    }    public function getServicesWithType($tenantId = null)
    {
        $builder = $this->db->table($this->table . ' s')
            ->select('s.*, st.txtName as service_type_name')
            ->join('m_service_types st', 's.intServiceTypeID = st.intServiceTypeID', 'left');
        
        if ($tenantId) {
            $builder->where('s.intTenantID', $tenantId);
        }
        
        return $builder->orderBy('s.txtName', 'ASC')->get()->getResultArray();
    }    public function getServiceDetails($id)
    {
        return $this->db->table($this->table . ' s')
            ->select('s.*, st.txtName as service_type_name, t.txtTenantName as tenant_name')
            ->join('m_service_types st', 's.intServiceTypeID = st.intServiceTypeID', 'left')
            ->join('m_tenants t', 's.intTenantID = t.intTenantID', 'left')
            ->where('s.intServiceID', $id)
            ->get()
            ->getRowArray();
    }

    public function getServicesWithAvailability($tenantId, $date = null)
    {
        $date = $date ?: date('Y-m-d');
          $builder = $this->db->table($this->table . ' s')
            ->select('s.*, st.txtName as service_type_name')
            ->join('m_service_types st', 's.intServiceTypeID = st.intServiceTypeID', 'left')
            ->where('s.intTenantID', $tenantId)
            ->where('s.bitActive', 1);
            
        $services = $builder->get()->getResultArray();
        
        // Get day of week
        $dayOfWeek = date('l', strtotime($date));
        
        foreach ($services as &$service) {
            // Get schedule for this day
            $scheduleBuilder = $this->db->table('m_schedules')
                ->where('intServiceID', $service['intServiceID'])
                ->where('txtDay', $dayOfWeek);
                
            $schedule = $scheduleBuilder->get()->getRowArray();
            
            if ($schedule) {
                $service['has_schedule'] = true;
                $service['dtmStartTime'] = $schedule['dtmStartTime'];
                $service['dtmEndTime'] = $schedule['dtmEndTime'];
                $service['intSlotDuration'] = $schedule['intSlotDuration'];
            } else {
                $service['has_schedule'] = false;
            }
            
            // Check for special schedules
            $specialBuilder = $this->db->table('m_schedule_specials')
                ->where('intServiceID', $service['intServiceID'])
                ->where('dtmDate', $date);
                
            $special = $specialBuilder->get()->getRowArray();
            
            if ($special) {
                $service['has_special'] = true;
                $service['bitIsClosed'] = $special['bitIsClosed'];
                if (!$special['bitIsClosed']) {
                    $service['dtmSpecialStartTime'] = $special['dtmStartTime'];
                    $service['dtmSpecialEndTime'] = $special['dtmEndTime'];
                }
            } else {
                $service['has_special'] = false;
            }
            
            // Get bookings for this service on this date
            $bookingBuilder = $this->db->table('tr_bookings')
                ->where('intServiceID', $service['intServiceID'])
                ->where('dtmBookingDate', $date)
                ->where('txtStatus !=', 'cancelled');
                
            $bookings = $bookingBuilder->get()->getResultArray();
            
            $service['booked_slots'] = $bookings;
        }
        
        return $services;
    }

    /**
     * Get all services with tenant and type information
     */
    public function getAllServicesWithTenant()
    {
        return $this->db->table($this->table . ' s')
            ->select('s.*, st.txtName as type_name, t.txtTenantName as tenant_name')
            ->join('m_service_types st', 's.intServiceTypeID = st.intServiceTypeID', 'left')
            ->join('m_tenants t', 's.intTenantID = t.intTenantID', 'left')
            ->orderBy('t.txtTenantName', 'ASC')
            ->orderBy('s.txtName', 'ASC')
            ->get()
            ->getResultArray();
    }
}
