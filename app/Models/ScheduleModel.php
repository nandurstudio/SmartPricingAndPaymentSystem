<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'm_schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'service_id',
        'day',
        'start_time',
        'end_time',
        'slot_duration', // in minutes
        'is_available',
        'created_by',
        'created_date',
        'updated_by',
        'updated_date'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_date';
    protected $updatedField = 'updated_date';

    // Get schedules for a specific service
    public function getServiceSchedules($tenantId, $serviceId = null, $date = null)
    {
        $builder = $this->db->table($this->table . ' sch')
            ->select('sch.*, s.name as service_name, s.tenant_id')
            ->join('m_services s', 'sch.service_id = s.id', 'left');
        
        // Filter by tenant
        $builder->where('s.tenant_id', $tenantId);
        
        // Filter by service if provided
        if ($serviceId) {
            $builder->where('sch.service_id', $serviceId);
        }
        
        // Get day of week if date is provided
        if ($date) {
            $dayOfWeek = date('l', strtotime($date));
            $builder->where('sch.day', $dayOfWeek);
        }
        
        $schedules = $builder->get()->getResultArray();
        
        // For each schedule, get the booking slots
        foreach ($schedules as &$schedule) {
            // Get bookings for this service and date
            if ($date) {
                $bookings = $this->getBookingsForDate($schedule['service_id'], $date);
                $schedule['slots'] = $this->generateTimeSlots(
                    $schedule['start_time'], 
                    $schedule['end_time'], 
                    $schedule['slot_duration'], 
                    $bookings
                );
                
                // Check for special schedule on this date
                $special = $this->getSpecialSchedule($schedule['service_id'], $date);
                $schedule['special'] = $special;
            }
        }
        
        return $schedules;
    }

    // Get special schedules (exceptions) for a service
    public function getServiceSpecialDates($serviceId, $month, $year)
    {
        if (!$serviceId) {
            return [];
        }
        
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return $this->db->table('m_schedule_specials')
            ->where('service_id', $serviceId)
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->orderBy('date', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Get special schedule for a specific service and date
    private function getSpecialSchedule($serviceId, $date)
    {
        return $this->db->table('m_schedule_specials')
            ->where('service_id', $serviceId)
            ->where('date', $date)
            ->get()
            ->getRowArray();
    }

    // Get bookings for a date
    private function getBookingsForDate($serviceId, $date)
    {
        return $this->db->table('m_bookings')
            ->select('start_time, end_time')
            ->where('service_id', $serviceId)
            ->where('booking_date', $date)
            ->where('status !=', 'cancelled')
            ->get()
            ->getResultArray();
    }

    // Generate time slots for a day with booked status
    private function generateTimeSlots($startTime, $endTime, $duration, $bookings)
    {
        $slots = [];
        $current = strtotime($startTime);
        $end = strtotime($endTime);
        
        while ($current < $end) {
            $slotStart = date('H:i', $current);
            $slotEnd = date('H:i', $current + ($duration * 60));
            
            // Check if this slot is booked
            $isBooked = false;
            foreach ($bookings as $booking) {
                $bookingStart = strtotime($booking['start_time']);
                $bookingEnd = strtotime($booking['end_time']);
                $slotStartTime = strtotime($slotStart);
                $slotEndTime = strtotime($slotEnd);
                
                // If booking overlaps with slot
                if ($bookingStart < $slotEndTime && $bookingEnd > $slotStartTime) {
                    $isBooked = true;
                    break;
                }
            }
            
            $slots[] = [
                'start' => $slotStart,
                'end' => $slotEnd,
                'status' => $isBooked ? 'booked' : 'available'
            ];
            
            $current += ($duration * 60);
        }
        
        return $slots;
    }
}
