<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'm_services';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tenant_id',
        'service_type_id',
        'name',
        'description',
        'price',
        'duration', // in minutes
        'capacity',
        'image',
        'is_active',
        'created_by',
        'created_date',
        'updated_by',
        'updated_date'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_date';
    protected $updatedField = 'updated_date';

    public function getServicesWithType($tenantId = null)
    {
        $builder = $this->db->table($this->table . ' s')
            ->select('s.*, st.name as type_name')
            ->join('m_service_types st', 's.service_type_id = st.id', 'left');
        
        if ($tenantId) {
            $builder->where('s.tenant_id', $tenantId);
        }
        
        return $builder->orderBy('s.name', 'ASC')->get()->getResultArray();
    }

    public function getServiceDetails($id)
    {
        return $this->db->table($this->table . ' s')
            ->select('s.*, st.name as type_name, t.name as tenant_name')
            ->join('m_service_types st', 's.service_type_id = st.id', 'left')
            ->join('m_tenants t', 's.tenant_id = t.id', 'left')
            ->where('s.id', $id)
            ->get()
            ->getRowArray();
    }

    // Get all active services for a tenant with availability info for a date
    public function getServicesWithAvailability($tenantId, $date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        $builder = $this->db->table($this->table . ' s')
            ->select('s.*, st.name as type_name')
            ->join('m_service_types st', 's.service_type_id = st.id', 'left')
            ->where('s.tenant_id', $tenantId)
            ->where('s.is_active', 1);
            
        $services = $builder->get()->getResultArray();
        
        // Get day of week
        $dayOfWeek = date('l', strtotime($date));
        
        foreach ($services as &$service) {
            // Get schedule for this day
            $scheduleBuilder = $this->db->table('m_schedules')
                ->where('service_id', $service['id'])
                ->where('day', $dayOfWeek);
                
            $schedule = $scheduleBuilder->get()->getRowArray();
            
            if ($schedule) {
                $service['has_schedule'] = true;
                $service['start_time'] = $schedule['start_time'];
                $service['end_time'] = $schedule['end_time'];
                $service['slot_duration'] = $schedule['slot_duration'];
            } else {
                $service['has_schedule'] = false;
            }
            
            // Check for special schedules
            $specialBuilder = $this->db->table('m_schedule_specials')
                ->where('service_id', $service['id'])
                ->where('date', $date);
                
            $special = $specialBuilder->get()->getRowArray();
            
            if ($special) {
                $service['has_special'] = true;
                $service['is_closed'] = $special['is_closed'];
                if (!$special['is_closed']) {
                    $service['special_start'] = $special['start_time'];
                    $service['special_end'] = $special['end_time'];
                }
            } else {
                $service['has_special'] = false;
            }
            
            // Get bookings for this service on this date
            $bookingBuilder = $this->db->table('m_bookings')
                ->where('service_id', $service['id'])
                ->where('booking_date', $date)
                ->where('status !=', 'cancelled');
                
            $bookings = $bookingBuilder->get()->getResultArray();
            
            $service['booked_slots'] = $bookings;
        }
        
        return $services;
    }
}
