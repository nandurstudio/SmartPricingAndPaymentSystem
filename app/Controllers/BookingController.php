<?php

namespace App\Controllers;

class BookingController extends BaseController
{
    protected $bookingModel;
    protected $serviceModel;
    protected $scheduleModel;

    public function __construct()
    {
        helper(['form', 'url', 'date']);
        // Note: We'll need to create these models
        // $this->bookingModel = new \App\Models\BookingModel();
        // $this->serviceModel = new \App\Models\ServiceModel();
        // $this->scheduleModel = new \App\Models\ScheduleModel();
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
        }

        // Get bookings based on role
        // For admin - all bookings
        // For tenant owner - bookings for their tenant
        // For customer - their own bookings
          // For now, just return dummy data until we create the models
        $data['bookings'] = [
            [
                'id' => 1,
                'booking_code' => 'BK2506001',
                'service_name' => 'Futsal Field A',
                'customer_name' => 'John Doe',
                'booking_date' => '2025-06-05',
                'start_time' => '15:00',
                'end_time' => '16:00',
                'price' => 150000,
                'price' => 150000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ],            [
                'id' => 2,
                'booking_code' => 'BK2506002',                'service_name' => 'Villa Anggrek',
                'customer_name' => 'Jane Smith',
                'booking_date' => '2025-06-10',
                'start_time' => '12:00',
                'end_time' => '12:00 (next day)',
                'price' => 1500000,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ],            [
                'id' => 3,
                'booking_code' => 'BK2506003',                'service_name' => 'Haircut & Styling',
                'customer_name' => 'Alex Johnson',
                'booking_date' => '2025-06-03',
                'start_time' => '10:30',
                'end_time' => '11:15',
                'price' => 75000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]
        ];

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

        // Get tenant ID
        $tenantId = $this->getTenantId();        $userId = session()->get('userID');
        $roleId = session()->get('roleID');

        if (!$tenantId && $roleId != 1) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage bookings.');
        }

        // Get services for dropdown
        // $data['services'] = $this->serviceModel->where('tenant_id', $tenantId)->findAll();
        
        // For now, use dummy data
        $data['services'] = [
            ['id' => 1, 'name' => 'Futsal Field A', 'price' => 150000, 'duration' => 60],
            ['id' => 2, 'name' => 'Villa Anggrek', 'price' => 1200000, 'duration' => 1440],
            ['id' => 3, 'name' => 'Haircut & Styling', 'price' => 75000, 'duration' => 45],
        ];

        // If admin, they need to select a customer
        if ($roleId == 1 || $roleId == 2) { // Admin or tenant owner
            // $data['customers'] = $this->userModel->where('roleID', 3)->findAll();
            
            // For now, use dummy data
            $data['customers'] = [
                ['id' => 101, 'name' => 'John Doe'],
                ['id' => 102, 'name' => 'Jane Smith'],
                ['id' => 103, 'name' => 'Alex Johnson'],
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
        }

        // Prepare data for booking creation
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
        
        // Get the service to determine duration
        // $service = $this->serviceModel->find($serviceId);
        
        // For now, use dummy data
        $service = [
            'id' => $serviceId,
            'duration' => 60, // minutes
            'price' => 150000
        ];

        // Calculate end time based on duration
        $startDateTime = new \DateTime("$bookingDate $startTime");
        $endDateTime = clone $startDateTime;
        $endDateTime->add(new \DateInterval('PT' . $service['duration'] . 'M')); // Add minutes

        // Check if the slot is available
        // $isAvailable = $this->scheduleModel->isSlotAvailable($serviceId, $bookingDate, $startTime, $endDateTime->format('H:i'));
        
        // if (!$isAvailable) {
        //     return redirect()->back()->withInput()->with('error', 'This slot is already booked. Please select a different time.');
        // }

        // Generate a unique booking code
        $bookingCode = $this->generateBookingCode();

        $data = [
            'service_id' => $serviceId,
            'customer_id' => $customerId,
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endDateTime->format('H:i'),
            'booking_code' => $bookingCode,
            'price' => $service['price'],
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'created_by' => $userId,
            'created_date' => date('Y-m-d H:i:s')
        ];

        // Insert booking data
        // $bookingId = $this->bookingModel->insert($data);

        // Process custom fields if any
        // $customFields = $this->request->getPost('custom');
        // if ($customFields && is_array($customFields)) {
        //     foreach ($customFields as $fieldId => $value) {
        //         $this->bookingCustomValueModel->insert([
        //             'booking_id' => $bookingId,
        //             'field_id' => $fieldId,
        //             'value' => $value,
        //             'created_by' => $userId,
        //             'created_date' => date('Y-m-d H:i:s')
        //         ]);
        //     }
        // }

        // In a real application, here we would redirect to payment gateway
        // For now, just redirect to booking list
        return redirect()->to('/booking')->with('success', 'Booking created successfully. Please complete the payment.');
    }

    public function view($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get the booking
        // $booking = $this->bookingModel->find($id);
        
        // For now, use dummy data
        $booking = [
            'id' => $id,
            'service_name' => 'Futsal Field A',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '081234567890',
            'booking_date' => '2025-06-05',
            'start_time' => '15:00',
            'end_time' => '16:00',
            'price' => 150000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'booking_code' => 'BK12345',
            'created_date' => '2025-05-30 14:22:33'
        ];
        
        // Check permissions - only admin, tenant owner of this service, or the booking owner can view
        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        
        // if (!$booking || 
        //     ($roleId != 1 && 
        //     !$this->isOwnerOfTenant($booking['service_id']) && 
        //     $booking['customer_id'] != $userId)) {
        //     return redirect()->to('/booking')->with('error', 'Booking not found or you do not have permission to view it.');
        // }

        // Get custom field values
        // $customValues = $this->bookingCustomValueModel->getBookingCustomValues($id);

        $data = [
            'title' => 'Booking Details',
            'pageTitle' => 'Booking Details',
            'pageSubTitle' => 'View booking information',
            'icon' => 'info-circle',
            'booking' => $booking,
            // 'customValues' => $customValues
        ];

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

        // Get the booking
        // $booking = $this->bookingModel->find($id);
        
        // For now, use dummy data
        $booking = [
            'id' => $id,
            'service_name' => 'Futsal Field A',
            'price' => 150000,
            'booking_code' => 'BK12345'
        ];
        
        // Check permissions - only admin, tenant owner of this service, or the booking owner can process payment
        $userId = session()->get('userID');
        $roleId = session()->get('roleID');
        
        // if (!$booking || 
        //     ($roleId != 1 && 
        //     !$this->isOwnerOfTenant($booking['service_id']) && 
        //     $booking['customer_id'] != $userId)) {
        //     return redirect()->to('/booking')->with('error', 'Booking not found or you do not have permission to process payment.');
        // }

        // Check if payment is needed
        // if ($booking['payment_status'] == 'paid') {
        //     return redirect()->to('/booking')->with('info', 'This booking has already been paid.');
        // }

        // Get tenant for payment settings
        // $tenantId = $this->getTenantIdFromService($booking['service_id']);
        // $tenant = $this->tenantModel->find($tenantId);

        $data = [
            'title' => 'Payment',
            'pageTitle' => 'Process Payment',
            'pageSubTitle' => 'Complete your booking payment',
            'icon' => 'credit-card',
            'booking' => $booking,
            // 'tenant' => $tenant
        ];

        // In a real application, here we would setup Midtrans or other payment gateway
        // For now, just show a payment form
        return view('booking/payment', $data);
    }

    /**
     * Get the tenant ID for the current user
     */
    private function getTenantId()
    {
        // For admin, they may need to select a tenant or see all tenants
        if (session()->get('roleID') == 1) {
            // For now, return a dummy tenant ID for admin
            return 1;
        }
        
        // For tenant owner, get their tenant
        $userId = session()->get('userID');
        
        // $tenant = $this->tenantModel->where('owner_id', $userId)->first();
        // return $tenant ? $tenant['id'] : null;
        
        // For now, return a dummy tenant ID
        return 1;
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
}
