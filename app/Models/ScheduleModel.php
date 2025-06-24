<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table = 'm_schedules';
    protected $primaryKey = 'intScheduleID';
    protected $allowedFields = [
        'intServiceID',
        'txtDay',
        'dtmStartTime',
        'dtmEndTime',
        'intSlotDuration', // in minutes
        'bitIsAvailable',
        'txtCreatedBy',
        'dtmCreatedDate',
        'txtUpdatedBy',
        'dtmUpdatedDate'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'dtmCreatedDate';
    protected $updatedField = 'dtmUpdatedDate';

    // Get schedules for a specific service
    public function getServiceSchedules($tenantId, $serviceId = null, $date = null)
    {
        $builder = $this->db->table($this->table . ' sch')
            ->select('sch.*, s.txtName as txtServiceName, s.intTenantID')
            ->join('m_services s', 'sch.intServiceID = s.intServiceID', 'left');
        
        // Filter by tenant
        $builder->where('s.intTenantID', $tenantId);
        
        // Filter by service if provided
        if ($serviceId) {
            $builder->where('sch.intServiceID', $serviceId);
        }
        
        // Get day of week if date is provided
        if ($date) {
            $dayOfWeek = date('l', strtotime($date));
            $builder->where('sch.txtDay', $dayOfWeek);
        }
        
        $schedules = $builder->get()->getResultArray();
        
        // For each schedule, get the booking slots
        foreach ($schedules as &$schedule) {
            // Get bookings for this service and date
            if ($date) {
                $bookings = $this->getBookingsForDate($schedule['intServiceID'], $date);
                $schedule['slots'] = $this->generateTimeSlots(
                    $schedule['dtmStartTime'], 
                    $schedule['dtmEndTime'], 
                    $schedule['intSlotDuration'], 
                    $bookings
                );
                
                // Check for special schedule on this date
                $special = $this->getSpecialSchedule($schedule['intServiceID'], $date);
                $schedule['special'] = $special;
            }
        }
        
        return $schedules;
    }

    // Get special schedules (exceptions) for a service
    public function getServiceSpecialDates($serviceId, $month, $year, $table = 'm_special_schedules')
    {
        if (!$serviceId) {
            return [];
        }
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        return $this->db->table($table)
            ->where('intServiceID', $serviceId)
            ->where('dtmSpecialDate >=', $startDate)
            ->where('dtmSpecialDate <=', $endDate)
            ->orderBy('dtmSpecialDate', 'ASC')
            ->get()
            ->getResultArray();
    }

    // Get special schedule for a specific service and date
    private function getSpecialSchedule($serviceId, $date)
    {
        return $this->db->table('m_schedule_specials')
            ->where('intServiceID', $serviceId)
            ->where('dtmDate', $date)
            ->get()
            ->getRowArray();
    }

    // Get bookings for a date
    private function getBookingsForDate($serviceId, $date)
    {
        return $this->db->table('tr_bookings')
            ->select('dtmStartTime, dtmEndTime')
            ->where('intServiceID', $serviceId)
            ->where('dtmBookingDate', $date)
            ->where('txtStatus !=', 'cancelled')
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
                $bookingStart = strtotime($booking['dtmStartTime']);
                $bookingEnd = strtotime($booking['dtmEndTime']);
                
                if ($current >= $bookingStart && $current < $bookingEnd) {
                    $isBooked = true;
                    break;
                }
            }
            
            $slots[] = [
                'dtmStartTime' => $slotStart,
                'dtmEndTime' => $slotEnd,
                'txtStatus' => $isBooked ? 'booked' : 'available'
            ];
            
            $current += ($duration * 60);
        }
        
        return $slots;
    }

    // Get a schedule with its service information
    public function getScheduleWithService($scheduleId)
    {
        return $this->db->table($this->table . ' sch')
            ->select('sch.*, s.txtName as txtServiceName, s.intTenantID')
            ->join('m_services s', 'sch.intServiceID = s.intServiceID', 'left')
            ->where('sch.intScheduleID', $scheduleId)
            ->get()
            ->getRowArray();
    }
}
