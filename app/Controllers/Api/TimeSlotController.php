<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class TimeSlotController extends ResourceController
{
    use ResponseTrait;

    protected $scheduleModel;
    protected $serviceModel;
    protected $bookingModel;
    protected $tenantModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        // Initialize models
        $this->scheduleModel = new \App\Models\ScheduleModel();
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->bookingModel = new \App\Models\BookingModel();
        $this->tenantModel = new \App\Models\MTenantModel();
        
        // Set response headers
        $this->response->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Headers', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Credentials', 'true');
            
        if ($this->request->getMethod() === 'options') {
            $this->response->setStatusCode(200);
            return $this->response;
        }
    }

    public function getAvailableSlots($serviceId)
    {
        try {
            // Validate service ID and date
            $date = $this->request->getGet('date');
            
            if (!$serviceId || !$date) {
                return $this->failValidationErrors('Service ID and date are required');
            }

            // Get tenant from subdomain
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $subdomain = explode('.', $host)[0];
            
            // Get tenant ID from subdomain
            $tenant = $this->tenantModel->where('txtSubdomain', $subdomain)->first();
            if (!$tenant) {
                return $this->failNotFound('Invalid tenant domain');
            }

            // Verify the service belongs to this tenant
            $service = $this->serviceModel
                ->where('intServiceID', $serviceId)
                ->where('intTenantID', $tenant['intTenantID'])
                ->where('bitActive', 1)
                ->first();

            if (!$service) {
                return $this->failNotFound('Service not found');
            }

            // Get day of week
            $dayOfWeek = date('l', strtotime($date));

            // Get regular schedule for this service and day
            $schedule = $this->scheduleModel
                ->where('intServiceID', $serviceId)
                ->where('txtDay', $dayOfWeek)
                ->where('bitIsAvailable', 1)
                ->first();

            // If no schedule found for this day
            if (!$schedule) {
                return $this->respond([
                    'slots' => [],
                    'message' => 'No schedule available for this day'
                ]);
            }

            // Check for special schedule
            $specialSchedule = $this->scheduleModel->getSpecialSchedule($serviceId, $date);
            if ($specialSchedule) {
                if ($specialSchedule['bitIsClosed']) {
                    return $this->respond([
                        'slots' => [],
                        'message' => 'Service is closed on this date'
                    ]);
                }
                // Use special schedule times if set
                if (!empty($specialSchedule['dtmStartTime']) && !empty($specialSchedule['dtmEndTime'])) {
                    $schedule['dtmStartTime'] = $specialSchedule['dtmStartTime'];
                    $schedule['dtmEndTime'] = $specialSchedule['dtmEndTime'];
                }
            }

            // Get existing bookings for this date
            $bookings = $this->bookingModel->getBookingsForDate($serviceId, $date);

            // Generate time slots
            $slots = [];
            $current = strtotime($schedule['dtmStartTime']);
            $end = strtotime($schedule['dtmEndTime']);
            $duration = $schedule['intSlotDuration'];

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
                
                // Only add future slots for today
                if ($date == date('Y-m-d') && $current < time()) {
                    $isBooked = true;
                }

                $slots[] = [
                    'time' => $slotStart,
                    'end_time' => $slotEnd,
                    'available' => !$isBooked
                ];
                
                $current += ($duration * 60);
            }

            return $this->respond([
                'slots' => $slots,
                'service' => [
                    'name' => $service['txtName'],
                    'duration' => $service['intDuration'],
                    'price' => $service['decPrice']
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[TimeSlotController::getAvailableSlots] Error: ' . $e->getMessage());
            return $this->failServerError('An error occurred while fetching time slots');
        }
    }
}
