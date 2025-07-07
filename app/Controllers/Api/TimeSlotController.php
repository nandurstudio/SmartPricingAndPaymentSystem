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
            // Enable error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            // Log input parameters
            log_message('debug', 'getAvailableSlots called with serviceId: ' . $serviceId);
            log_message('debug', 'Date parameter: ' . $this->request->getGet('date'));
            
            // Validate service ID and date
            $date = $this->request->getGet('date');
            
            if (!$serviceId || !$date) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Service ID and date are required'
                ]);
            }

            // Get tenant from subdomain or parameter
            $tenantId = null;
            
            // Check if we're on a tenant subdomain
            $host = $_SERVER['HTTP_HOST'] ?? '';
            $baseDomain = env('BASE_DOMAIN', 'smartpricingandpaymentsystem.localhost.com');
            
            if ($host && strpos($host, $baseDomain)) {
                $subdomain = str_replace('.' . $baseDomain, '', $host);
                $tenant = $this->tenantModel
                    ->where('txtDomain', $subdomain)
                    ->where('bitActive', 1)
                    ->where('txtStatus', 'active')
                    ->first();
                    
                if ($tenant) {
                    $tenantId = $tenant['intTenantID'];
                }
            }
            
            // If not on tenant domain or tenant not found, try to get service directly
            try {
                $serviceQuery = $this->serviceModel
                    ->where('intServiceID', $serviceId)
                    ->where('bitActive', 1);
                    
                // If we have a tenant ID, filter by it
                if ($tenantId) {
                    $serviceQuery = $serviceQuery->where('intTenantID', $tenantId);
                }
                
                // Log the query for debugging
                log_message('debug', 'Service query: ' . $serviceQuery->getCompiledSelect(false));
                
                $service = $serviceQuery->first();
                
                // Log service data
                log_message('debug', 'Service found: ' . json_encode($service));

                if (!$service) {
                    return $this->respond([
                        'status' => 'error',
                        'message' => 'Service not found'
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Error querying service: ' . $e->getMessage());
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Error finding service: ' . $e->getMessage()
                ]);
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
                    'status' => 'success',
                    'data' => [],
                    'message' => 'No schedule available for this day'
                ]);
            }

            // Check for special schedule
            $specialSchedule = $this->scheduleModel->getSpecialSchedule($serviceId, $date);
            if ($specialSchedule) {
                if ($specialSchedule['bitIsClosed']) {
                    return $this->respond([
                        'status' => 'success',
                        'data' => [],
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
                'status' => 'success',
                'data' => $slots,
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
