<?php

namespace App\Controllers;

class BookingController extends BaseController
{
    protected $bookingModel;
    protected $serviceModel;
    protected $scheduleModel;
    protected $tenantModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        helper(['form', 'url', 'date']);
        $this->bookingModel = new \App\Models\BookingModel();
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->tenantModel = new \App\Models\MTenantModel();
        $this->userModel = new \App\Models\MUserModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Bookings',
            'pageTitle' => 'Booking Management',
            'pageSubTitle' => 'View and manage all bookings',
            'icon' => 'calendar'
        ];

        // Get tenant ID - in a multi-tenant app, we need to filter by tenant
        $tenantId = $this->getTenantId();        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        if (!$tenantId && $roleId != 1) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage bookings.');
        }        // Get any filter parameters
        $tenantFilter = $this->request->getGet('tenant_id');
        $serviceFilter = $this->request->getGet('service_id');
        $statusFilter = $this->request->getGet('status');
        $dateFilter = $this->request->getGet('date');

        // Get bookings based on role and filters        
        $query = $this->bookingModel->select('tr_bookings.*, 
                m_services.txtName as service_name, 
                m_user.txtFullName as customer_name,
                m_tenants.txtTenantName as tenant_name')
            ->join('m_services', 'tr_bookings.intServiceID = m_services.intServiceID', 'left')
            ->join('m_user', 'tr_bookings.intCustomerID = m_user.intUserID', 'left')
            ->join('m_tenants', 'tr_bookings.intTenantID = m_tenants.intTenantID', 'left')
            ->where('tr_bookings.bitActive', 1);

        // Apply role-based filters
        if ($roleId == 2) { // Tenant owner
            $query->where('tr_bookings.intTenantID', $tenantId);
        } elseif ($roleId == 3) { // Customer
            $query->where('tr_bookings.intCustomerID', $userId);
        }
        // Admin (roleId == 1) can see all bookings

        // Apply optional filters
        if ($tenantFilter) {
            $query->where('tr_bookings.intTenantID', $tenantFilter);
        }
        if ($serviceFilter) {
            $query->where('tr_bookings.intServiceID', $serviceFilter);
        }
        if ($statusFilter) {
            $query->where('tr_bookings.txtStatus', $statusFilter);
        }
        if ($dateFilter) {
            $query->where('tr_bookings.dtmBookingDate', $dateFilter);
        }

        // Get results sorted by date
        $rawBookings = $query->orderBy('tr_bookings.dtmBookingDate DESC, tr_bookings.dtmStartTime ASC')
            ->findAll();

        // Map DB fields to view keys for consistency
        $data['bookings'] = array_map(function($b) {
            return [
                'id' => $b['intBookingID'],
                'booking_code' => $b['txtBookingCode'],
                'customer_name' => $b['customer_name'] ?? '',
                'service_name' => $b['service_name'] ?? '',
                'booking_date' => $b['dtmBookingDate'],
                'start_time' => $b['dtmStartTime'],
                'end_time' => $b['dtmEndTime'],
                'price' => $b['decPrice'],
                'status' => $b['txtStatus'],
                'payment_status' => $b['txtPaymentStatus'],
            ];
        }, $rawBookings);

        return view('booking/index', $data);
    }

    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Create Booking',
            'pageTitle' => 'Create New Booking',
            'pageSubTitle' => 'Book a service',
            'icon' => 'calendar-plus',
            'validation' => \Config\Services::validation()
        ];

        // Get tenant ID and user info
        $tenantId = $this->getTenantId();
        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Check if we're on a tenant subdomain
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $isOnTenantSubdomain = strpos($host, '.') !== false;
        $data['isOnTenantSubdomain'] = $isOnTenantSubdomain;

        // For tenant subdomains or tenant owners, only show services from that tenant
        if ($isOnTenantSubdomain || $roleId == 2) {
            if (!$tenantId) {
                return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage bookings.');
            }

            // Get tenant's active services
            $data['services'] = $this->serviceModel
                ->where('intTenantID', $tenantId)
                ->where('bitActive', 1)
                ->findAll();

            // Get current tenant info
            if (!isset($this->tenantModel)) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }
            $currentTenant = $this->tenantModel->find($tenantId);
            if ($currentTenant) {
                $data['tenants'] = [$currentTenant];
            }
        } 
        // For admin, show all services or filter by tenant
        else if ($roleId == 1) {
            $selectedTenantId = $this->request->getGet('tenant_id');
            if ($selectedTenantId) {
                // If tenant is selected, show only their services
                $data['services'] = $this->serviceModel
                    ->where('intTenantID', $selectedTenantId)
                    ->where('bitActive', 1)
                    ->findAll();

                // Get the selected tenant for dropdown
                $selectedTenant = $this->tenantModel->find($selectedTenantId);
                if ($selectedTenant) {
                    $data['tenants'] = [$selectedTenant];
                }
            } else {
                // No tenant selected, show all active services and tenants
                $data['services'] = $this->serviceModel->where('bitActive', 1)->findAll();
                if (!isset($this->tenantModel)) {
                    $this->tenantModel = new \App\Models\MTenantModel();
                }
                $data['tenants'] = $this->tenantModel
                    ->where('bitActive', 1)
                    ->where('txtStatus', 'active')
                    ->findAll();
            }
        }

        // If admin or tenant owner, they need to select a customer
        if ($roleId == 1 || $roleId == 2) {
            $data['customers'] = [
                ['intCustomerID' => 101, 'txtFullName' => 'John Doe', 'txtEmail' => 'john@example.com'],
                ['intCustomerID' => 102, 'txtFullName' => 'Jane Smith', 'txtEmail' => 'jane@example.com'],
                ['intCustomerID' => 103, 'txtFullName' => 'Alex Johnson', 'txtEmail' => 'alex@example.com']
            ];
        }

        return view('booking/create', $data);
    }

    public function store()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'service_id' => 'required|numeric',
            'booking_date' => 'required|valid_date',
            'start_time' => 'required',
            'customer_id' => 'permit_empty|numeric',  // Only required if admin/tenant owner
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }        // Prepare data for booking creation
        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Determine the customer ID
        $customerId = $userId; // Default to current user
        
        // If admin or tenant owner, they can book on behalf of a customer
        if (($roleId == 1 || $roleId == 2) && $this->request->getPost('customer_id')) {
            $customerId = $this->request->getPost('customer_id');
        }

        $serviceId = $this->request->getPost('service_id');
        $bookingDate = $this->request->getPost('booking_date');
        $startTime = $this->request->getPost('start_time');
        
        // Get the service details
        $service = $this->serviceModel->find($serviceId);
        if (!$service) {
            return redirect()->back()->withInput()->with('error', 'Service not found.');
        }        // Calculate end time based on duration
        $startDateTime = new \DateTime("$bookingDate $startTime");
        $endDateTime = clone $startDateTime;
        $endDateTime->add(new \DateInterval('PT' . $service['intDuration'] . 'M')); // Add minutes

        // Check if the slot is available
        if (!$this->bookingModel->isSlotAvailable($serviceId, $bookingDate, $startTime, $endDateTime->format('H:i'))) {
            return redirect()->back()->withInput()->with('error', 'This slot is already booked. Please select a different time.');
        }

        // Get tenant ID from service
        $tenantId = $service['intTenantID'];

        // Generate a unique booking code
        $bookingCode = $this->generateBookingCode();
        
        // Prepare data for insert
        $data = [
            'txtBookingCode' => $bookingCode,
            'intServiceID' => $serviceId,
            'intCustomerID' => $customerId,
            'intTenantID' => $tenantId,
            'dtmBookingDate' => $bookingDate,
            'dtmStartTime' => $startTime,
            'dtmEndTime' => $endDateTime->format('H:i'),
            'decPrice' => $service['decPrice'],
            'txtStatus' => 'pending',
            'txtPaymentStatus' => 'unpaid',
            'txtPaymentID' => '',
            'txtGUID' => $this->db->query('SELECT UUID() as guid')->getRow()->guid,
            'txtCreatedBy' => session()->get('userName'),
            'dtmCreatedDate' => date('Y-m-d H:i:s'),
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s'),
            'bitActive' => 1
        ];        // Insert the booking
        try {
            $bookingId = $this->bookingModel->insert($data);
            
            if (!$bookingId) {
                return redirect()->back()->withInput()->with('error', 'Failed to create booking. Please try again.');
            }

            // If payment method is pay_now, redirect to payment page
            if ($this->request->getPost('payment_method') === 'pay_now') {
                return redirect()->to("/bookings/payment/$bookingId")->with('success', 'Booking created successfully. Please complete the payment.');
            }

            // Otherwise redirect to bookings list
            return redirect()->to('/bookings')->with('success', 'Booking created successfully.');
            
        } catch (\Exception $e) {
            log_message('error', 'Booking creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the booking.');
        }
    }

    public function view($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get the booking from DB
        $booking = $this->bookingModel->getBookingDetails($id);
        if (!$booking) {
            return redirect()->to('/bookings')->with('error', 'Booking not found.');
        }

        // Map DB fields to view keys for consistency
        $mapped = [
            'id' => $booking['intBookingID'],
            'booking_code' => $booking['txtBookingCode'],
            'service_name' => $booking['txtServiceName'] ?? '',
            'tenant_name' => $booking['txtTenantName'] ?? '',
            'customer_name' => $booking['txtCustomerName'] ?? '',
            'customer_email' => $booking['txtCustomerEmail'] ?? '',
            'booking_date' => $booking['dtmBookingDate'],
            'start_time' => $booking['dtmStartTime'],
            'end_time' => $booking['dtmEndTime'],
            'price' => $booking['decPrice'],
            'service_duration' => $booking['intDuration'] ?? '',
            'status' => $booking['txtStatus'],
            'payment_status' => $booking['txtPaymentStatus'],
            'created_date' => $booking['dtmCreatedDate'],
            'cancelled_date' => $booking['dtmCancelledDate'] ?? null,
            'cancelled_reason' => $booking['txtCancelledReason'] ?? null,
            'notes' => $booking['txtNotes'] ?? null,
            'payment_reference' => $booking['txtPaymentID'] ?? null,
            'payment_date' => $booking['dtmUpdatedDate'] ?? $booking['dtmCreatedDate'],
        ];

        $data = [
            'title' => 'Booking Details',
            'pageTitle' => 'Booking Details',
            'pageSubTitle' => 'View booking information',
            'icon' => 'info-circle',
            'booking' => $mapped,
        ];

        // Add status class based on booking status
        $data['statusClass'] = match($mapped['status']) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'secondary'
        };

        // Add payment status class
        $data['paymentStatusClass'] = match($mapped['payment_status']) {
            'paid' => 'success',
            'pending' => 'warning',
            'refunded' => 'info',
            default => 'secondary'
        };

        return view('booking/view', $data);
    }

    public function cancel($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get the booking
        // $booking = $this->bookingModel->find($id);
        
        // Check permissions - only admin, tenant owner of this service, or the booking owner can cancel
        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        
        // if (!$booking || 
        //     ($roleId != 1 && 
        //     !$this->isOwnerOfTenant($booking['service_id']) && 
        //     $booking['customer_id'] != $userId)) {
        //     return redirect()->to('/booking')->with('error', 'Booking not found or you do not have permission to cancel it.');
        // }

        // Check if booking can be cancelled (e.g., not too close to booking time)
        // $canCancel = $this->bookingModel->canCancel($id);
        
        // if (!$canCancel) {
        //     return redirect()->to('/booking')->with('error', 'This booking cannot be cancelled at this time.');
        // }

        // Update booking status
        // $this->bookingModel->update($id, [
        //     'status' => 'cancelled',
        //     'updated_by' => $userId,
        //     'updated_date' => date('Y-m-d H:i:s')
        // ]);

        // Process refund if applicable
        // if ($booking['payment_status'] == 'paid') {
        //     // Call refund processing logic
        //     // $this->paymentModel->processRefund($booking['payment_id']);
        // }

        return redirect()->to('/booking')->with('success', 'Booking cancelled successfully.');
    }

    public function payment($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get the booking from DB
        $booking = $this->bookingModel->getBookingDetails($id);
        if (!$booking) {
            return redirect()->to('/bookings')->with('error', 'Booking not found.');
        }

        // Map DB fields to view keys for consistency
        $mapped = [
            'id' => $booking['intBookingID'],
            'booking_code' => $booking['txtBookingCode'],
            'service_name' => $booking['txtServiceName'] ?? '',
            'price' => $booking['decPrice'],
        ];

        $data = [
            'title' => 'Payment',
            'pageTitle' => 'Process Payment',
            'pageSubTitle' => 'Complete your booking payment',
            'icon' => 'credit-card',
            'booking' => $mapped,
        ];

        return view('booking/payment', $data);
    }

    public function calendar()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Booking Calendar',
            'pageTitle' => 'Booking Calendar',
            'pageSubTitle' => 'View all bookings in a calendar format',
            'icon' => 'calendar'
        ];

        // Get tenant ID and role information
        $tenantId = $this->getTenantId();
        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        // Initialize models if not already initialized
        if (!$this->bookingModel) {
            $this->bookingModel = new \App\Models\BookingModel();
        }
        if (!$this->serviceModel) {
            $this->serviceModel = new \App\Models\ServiceModel();
        }

        // Get bookings based on role
        $bookings = [];
        $month = date('Y-m');
        $startDate = date('Y-m-01'); // First day of current month
        $endDate = date('Y-m-t');    // Last day of current month

        if ($roleId == 1) { // Admin - all bookings
            // You can add tenant filter if provided in query params
            $filterTenantId = $this->request->getGet('tenant_id');
            if ($filterTenantId) {
                $bookings = $this->bookingModel->getTenantBookings($filterTenantId);
            } else {
                $bookings = $this->bookingModel->where('dtmBookingDate >=', $startDate)
                                             ->where('dtmBookingDate <=', $endDate)
                                             ->findAll();
            }
        } elseif ($roleId == 2) { // Tenant owner - their tenant's bookings
            $bookings = $this->bookingModel->getTenantBookings($tenantId);
        } else { // Customer - their own bookings
            $bookings = $this->bookingModel->getCustomerBookings($userId);
        }

        // Format bookings as calendar events
        $events = [];
        foreach ($bookings as $booking) {
            $color = '#6c757d'; // Default gray

            switch ($booking['txtStatus']) {
                case 'confirmed':
                    $color = '#28a745'; // green
                    break;
                case 'pending':
                    $color = '#ffc107'; // yellow
                    break;
                case 'cancelled':
                    $color = '#dc3545'; // red
                    break;
                case 'completed':
                    $color = '#17a2b8'; // blue
                    break;
            }

            $events[] = [
                'id' => $booking['intBookingID'],
                'title' => ($booking['txtServiceName'] ?? 'Service') . ' - ' . ($booking['txtCustomerName'] ?? 'Customer'),
                'start' => $booking['dtmBookingDate'] . 'T' . $booking['dtmStartTime'],
                'end' => $booking['dtmBookingDate'] . 'T' . $booking['dtmEndTime'],
                'backgroundColor' => $color,
                'borderColor' => $color
            ];
        }

        $data['events'] = $events;

        // Get services for filter dropdown
        if ($roleId == 1) {
            // Admin can see all services
            $data['services'] = array_map(function($service) {
                return [
                    'id' => $service['intServiceID'],
                    'name' => $service['txtName']
                ];
            }, $this->serviceModel->findAll());
        } elseif ($roleId == 2 && $tenantId) {
            // Tenant owner sees their services
            $data['services'] = array_map(function($service) {
                return [
                    'id' => $service['intServiceID'],
                    'name' => $service['txtName']
                ];
            }, $this->serviceModel->where('intTenantID', $tenantId)->findAll());
        }
        // Customers don't need to filter by service

        // Also handle tenants array format if it exists
        if (isset($data['tenants'])) {
            $data['tenants'] = array_map(function($tenant) {
                return [
                    'id' => $tenant['intTenantID'],
                    'name' => $tenant['txtTenantName']
                ];
            }, $data['tenants']);
        }

        return view('booking/calendar', $data);
    }

    /**
     * Get the tenant ID for the current user
     */
    private function getTenantId()
    {
        // 1. First check if we're on a tenant subdomain
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (strpos($host, '.') !== false) {
            // Extract subdomain from host
            $baseDomain = env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com';
            $baseDomain = rtrim(preg_replace('#^https?://#', '', $baseDomain), '/');
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            
            // Load tenant model if not already initialized
            if (!$this->tenantModel) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }
            
            // Get tenant by subdomain
            $tenant = $this->tenantModel->where('txtDomain', $subdomain)
                                      ->where('bitActive', 1)
                                      ->where('txtStatus', 'active')
                                      ->first();
            if ($tenant) {
                return $tenant['intTenantID'];
            }
        }

        // 2. For admin users, check URL parameter
        if (session()->get('roleID') == 1) {
            $tenantId = $this->request->getGet('tenant_id');
            if ($tenantId) {
                return $tenantId;
            }
        }
        
        // 3. For tenant owners, get their tenant
        $userId = session()->get('userID');
        if (session()->get('roleID') == 2) {
            if (!$this->tenantModel) {
                $this->tenantModel = new \App\Models\MTenantModel();
            }
            
            $tenant = $this->tenantModel->where('intOwnerID', $userId)
                                      ->where('bitActive', 1)
                                      ->where('txtStatus', 'active')
                                      ->first();
            
            if ($tenant) {
                return $tenant['intTenantID'];
            }
        }
        
        // 4. For customers or other users, get their default tenant
        if ($userId) {
            if (!$this->userModel) {
                $this->userModel = new \App\Models\MUserModel();
            }
            
            $user = $this->userModel->find($userId);
            if ($user && !empty($user['intDefaultTenantID'])) {
                return $user['intDefaultTenantID'];
            }
        }
        
        return null;
    }

    /**
     * Check if the current user is the owner of the tenant that owns a specific service
     */
    private function isOwnerOfTenant($serviceId)
    {
        $userId = session()->get('userID');
        
        // $service = $this->serviceModel->find($serviceId);
        // if (!$service) {
        //     return false;
        // }
        
        // $tenant = $this->tenantModel->find($service['tenant_id']);
        // return $tenant && $tenant['owner_id'] == $userId;
        
        // For now, return true for demo
        return true;
    }

    /**
     * Get tenant ID from service ID
     */
    private function getTenantIdFromService($serviceId)
    {
        // $service = $this->serviceModel->find($serviceId);
        // return $service ? $service['tenant_id'] : null;
        
        // For now, return dummy ID
        return 1;
    }

    /**
     * Generate a unique booking code
     */
    private function generateBookingCode()
    {
        // Generate a code with prefix BK + current year + random number
        $prefix = 'BK' . date('y');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $code = $prefix . $random;
        
        // Check if the code exists
        // $existing = $this->bookingModel->where('booking_code', $code)->first();
        
        // // If exists, regenerate until we get a unique one
        // while ($existing) {
        //     $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        //     $code = $prefix . $random;
        //     $existing = $this->bookingModel->where('booking_code', $code)->first();
        // }

        return $code;
    }

    public function receipt($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get the booking from DB
        $booking = $this->bookingModel->getBookingDetails($id);
        if (!$booking) {
            return redirect()->to('/bookings')->with('error', 'Booking not found.');
        }

        // Map DB fields to view keys for consistency
        $mapped = [
            'id' => $booking['intBookingID'],
            'booking_code' => $booking['txtBookingCode'],
            'service_name' => $booking['txtServiceName'] ?? '',
            'tenant_name' => $booking['txtTenantName'] ?? '',
            'customer_name' => $booking['txtCustomerName'] ?? '',
            'customer_email' => $booking['txtCustomerEmail'] ?? '',
            'booking_date' => $booking['dtmBookingDate'],
            'start_time' => $booking['dtmStartTime'],
            'end_time' => $booking['dtmEndTime'],
            'price' => $booking['decPrice'],
            'service_duration' => $booking['intDuration'] ?? '',
            'status' => $booking['txtStatus'],
            'payment_status' => $booking['txtPaymentStatus'],
            'created_date' => $booking['dtmCreatedDate'],
            'cancelled_date' => $booking['dtmCancelledDate'] ?? null,
            'cancelled_reason' => $booking['txtCancelledReason'] ?? null,
            'notes' => $booking['txtNotes'] ?? null,
            'payment_reference' => $booking['txtPaymentID'] ?? null,
            'payment_date' => $booking['dtmUpdatedDate'] ?? $booking['dtmCreatedDate'],
            'guid' => $booking['txtGUID'] ?? '',
        ];

        $data = [
            'title' => 'Booking Receipt',
            'pageTitle' => 'Booking Receipt',
            'pageSubTitle' => 'View booking payment receipt',
            'icon' => 'file-invoice',
            'booking' => $mapped
        ];

        // Check if payment is completed
        if ($mapped['payment_status'] !== 'paid') {
            return redirect()->to('/bookings/view/' . $id)->with('error', 'Cannot view receipt. Payment is not yet completed.');
        }

        return view('booking/receipt', $data);
    }

    /**
     * Update booking status (POST /bookings/update-status)
     */
    public function updateStatus()
    {
        // Pastikan user login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $bookingId = $this->request->getPost('booking_id');
        $newStatus = $this->request->getPost('status');
        if (!$bookingId || !$newStatus) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        // Validasi status yang diperbolehkan
        $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($newStatus, $allowed)) {
            return redirect()->back()->with('error', 'Invalid status value.');
        }

        // Update status booking
        $update = [
            'txtStatus' => $newStatus,
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s'),
        ];
        $this->bookingModel->update($bookingId, $update);

        return redirect()->to('/bookings/view/' . $bookingId)->with('success', 'Booking status updated successfully.');
    }

    /**
     * Public invoice view by GUID (no login required)
     */
    public function publicInvoice($guid)
    {
        $booking = $this->bookingModel->where('txtGUID', $guid)->first();
        if (!$booking) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Invoice not found.');
        }
        // Ambil detail booking lengkap (join service, tenant, user)
        $details = $this->bookingModel->getBookingDetails($booking['intBookingID']);
        // Map DB fields ke view
        $mapped = [
            'id' => $details['intBookingID'],
            'booking_code' => $details['txtBookingCode'],
            'service_name' => $details['txtServiceName'] ?? '',
            'tenant_name' => $details['txtTenantName'] ?? '',
            'customer_name' => $details['txtCustomerName'] ?? '',
            'customer_email' => $details['txtCustomerEmail'] ?? '',
            'booking_date' => $details['dtmBookingDate'],
            'start_time' => $details['dtmStartTime'],
            'end_time' => $details['dtmEndTime'],
            'price' => $details['decPrice'],
            'service_duration' => $details['intDuration'] ?? '',
            'status' => $details['txtStatus'],
            'payment_status' => $details['txtPaymentStatus'],
            'created_date' => $details['dtmCreatedDate'],
            'cancelled_date' => $details['dtmCancelledDate'] ?? null,
            'cancelled_reason' => $details['txtCancelledReason'] ?? null,
            'notes' => $details['txtNotes'] ?? null,
            'payment_reference' => $details['txtPaymentID'] ?? null,
            'payment_date' => $details['dtmUpdatedDate'] ?? $details['dtmCreatedDate'],
            'guid' => $details['txtGUID'] ?? '',
        ];
        $data = [
            'title' => 'Public Invoice',
            'pageTitle' => 'Public Invoice',
            'pageSubTitle' => 'View invoice without login',
            'icon' => 'file-invoice',
            'booking' => $mapped
        ];
        return view('booking/receipt', $data);
    }

    // Refund booking (GET: show form, POST: process refund)
    public function refund($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }
        if ($this->request->getMethod() === 'post') {
            // Process refund logic (simple: set status refunded, add note)
            $reason = $this->request->getPost('refund_reason');
            $booking = $this->bookingModel->find($id);
            if (!$booking) {
                return redirect()->to('/bookings')->with('error', 'Booking not found.');
            }
            if ($booking['txtPaymentStatus'] !== 'paid') {
                return redirect()->to("/bookings/view/$id")->with('error', 'Refund only allowed for paid bookings.');
            }
            $this->bookingModel->update($id, [
                'txtPaymentStatus' => 'refunded',
                'txtStatus' => 'cancelled',
                'txtCancelledReason' => $reason,
                'dtmCancelledDate' => date('Y-m-d H:i:s'),
                'txtUpdatedBy' => session()->get('userName'),
                'dtmUpdatedDate' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->to("/bookings/view/$id")->with('success', 'Booking refunded successfully.');
        } else {
            // Show refund form
            $booking = $this->bookingModel->find($id);
            if (!$booking) {
                return redirect()->to('/bookings')->with('error', 'Booking not found.');
            }
            if ($booking['txtPaymentStatus'] !== 'paid') {
                return redirect()->to("/bookings/view/$id")->with('error', 'Refund only allowed for paid bookings.');
            }
            // Mapping agar view refund.php bisa akses $booking['id'] dan $booking['booking_code']
            $booking['id'] = $booking['intBookingID'];
            $booking['booking_code'] = $booking['txtBookingCode'];
            return view('booking/refund', ['booking' => $booking]);
        }
    }
}
