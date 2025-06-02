<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class TimeSlotController extends ResourceController
{
    use ResponseTrait;

    protected $scheduleModel;
    protected $serviceModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->scheduleModel = new \App\Models\ScheduleModel();
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->bookingModel = new \App\Models\BookingModel();
    }

    public function getAvailableSlots($serviceId)
    {
        // Validate service ID and date
        $date = $this->request->getGet('date');
        
        if (!$serviceId || !$date) {
            return $this->respond([
                'error' => 'Service ID and date are required'
            ], 400);
        }

        // Get day of week
        $dayOfWeek = date('l', strtotime($date));

        // Get regular schedule for this service and day
        $schedule = $this->scheduleModel->where('service_id', $serviceId)
            ->where('day', $dayOfWeek)
            ->first();

        // If no schedule found for this day
        if (!$schedule) {
            return $this->respond(['slots' => []]);
        }

        // Check for special schedule
        $specialSchedule = $this->scheduleModel->getSpecialSchedule($serviceId, $date);
        if ($specialSchedule) {
            if ($specialSchedule['is_closed']) {
                return $this->respond(['slots' => []]);
            }
            // Use special schedule times if set
            if (!empty($specialSchedule['start_time']) && !empty($specialSchedule['end_time'])) {
                $schedule['start_time'] = $specialSchedule['start_time'];
                $schedule['end_time'] = $specialSchedule['end_time'];
            }
        }

        // Get existing bookings for this date
        $bookings = $this->bookingModel->getBookingsForDate($serviceId, $date);

        // Generate time slots
        $slots = $this->scheduleModel->generateTimeSlots(
            $schedule['start_time'],
            $schedule['end_time'], 
            $schedule['slot_duration'],
            $bookings
        );

        // Format slots for response
        $formattedSlots = array_map(function($slot) {
            return [
                'time' => $slot['start'],
                'end_time' => $slot['end'],
                'available' => $slot['status'] === 'available'
            ];
        }, $slots);

        return $this->respond(['slots' => $formattedSlots]);
    }
}
