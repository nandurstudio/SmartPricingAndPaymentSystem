<?php

namespace App\Controllers;

class ScheduleController extends BaseController
{    protected $scheduleModel;
    protected $serviceModel;
    protected $bookingModel;

    public function __construct()
    {
        helper(['form', 'url', 'date']);
        $this->scheduleModel = new \App\Models\ScheduleModel();
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->bookingModel = new \App\Models\BookingModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Schedules',
            'pageTitle' => 'Schedule Management',
            'pageSubTitle' => 'View and manage service availability',
            'icon' => 'clock'
        ];

        // Get tenant ID - in a multi-tenant app, we need to filter by tenant
        $tenantId = $this->getTenantId();
        $roleId = session()->get('roleID');        if (!$tenantId && $roleId != 1) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage schedules.');
        }        // Get services for filtering
        $data['services'] = $this->serviceModel->where('intTenantID', $tenantId)->findAll();
        
        // Get service schedules
        $serviceId = $this->request->getGet('service_id');
        $data['schedules'] = $this->scheduleModel->getServiceSchedules($tenantId, $serviceId);
        
        // Set selected service for filters
        $data['selectedServiceId'] = $serviceId;

        return view('schedules/index', $data);
    }

    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get tenant ID
        $tenantId = $this->getTenantId();
        $roleId = session()->get('roleID');

        if (!$tenantId && $roleId != 1) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage schedules.');
        }

        $data = [
            'title' => 'Create Schedule',
            'pageTitle' => 'Create New Schedule',
            'pageSubTitle' => 'Define service availability',
            'icon' => 'plus-circle',
            'validation' => \Config\Services::validation()
        ];

        // Get services for dropdown
        $data['services'] = $this->serviceModel->where('intTenantID', $tenantId)
            ->where('bitActive', 1)
            ->findAll();

        // Days of week for dropdown
        $data['days'] = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ];

        return view('schedules/create', $data);
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
            'days' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_duration' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $serviceId = $this->request->getPost('service_id');
        $days = $this->request->getPost('days');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');
        $slotDuration = $this->request->getPost('slot_duration');
        $userId = session()->get('userID');

        // Ensure service belongs to tenant
        // $service = $this->serviceModel->find($serviceId);
        // $tenantId = $this->getTenantId();
        
        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->back()->with('error', 'Service not found or you do not have permission to manage its schedule.');
        // }

        // Create schedule for each selected day
        foreach ($days as $day) {
            // Check if schedule already exists
            // $existingSchedule = $this->scheduleModel
            //     ->where('service_id', $serviceId)
            //     ->where('day', $day)
            //     ->first();
            
            // if ($existingSchedule) {
            //     // Update existing schedule
            //     $this->scheduleModel->update($existingSchedule['id'], [
            //         'start_time' => $startTime,
            //         'end_time' => $endTime,
            //         'slot_duration' => $slotDuration,
            //         'is_available' => 1,
            //         'updated_by' => $userId,
            //         'updated_date' => date('Y-m-d H:i:s')
            //     ]);
            // } else {
            //     // Insert new schedule
            //     $this->scheduleModel->insert([
            //         'service_id' => $serviceId,
            //         'day' => $day,
            //         'start_time' => $startTime,
            //         'end_time' => $endTime,
            //         'slot_duration' => $slotDuration,
            //         'is_available' => 1,
            //         'created_by' => $userId,
            //         'created_date' => date('Y-m-d H:i:s')
            //     ]);
            // }
        }

        return redirect()->to('/schedules?service_id=' . $serviceId)->with('success', 'Schedule created successfully.');
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Fetch the schedule
        // $schedule = $this->scheduleModel->find($id);
        
        // For now, use dummy data
        $schedule = [
            'id' => $id,
            'service_id' => 1,
            'day' => 'Monday',
            'start_time' => '08:00',
            'end_time' => '22:00',
            'slot_duration' => 60,
            'is_available' => true
        ];
        
        // Check if schedule exists
        // if (!$schedule) {
        //     return redirect()->to('/schedule')->with('error', 'Schedule not found.');
        // }

        // Ensure service belongs to tenant
        // $service = $this->serviceModel->find($schedule['service_id']);
        // $tenantId = $this->getTenantId();
        
        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->to('/schedule')->with('error', 'You do not have permission to edit this schedule.');
        // }

        $data = [
            'title' => 'Edit Schedule',
            'pageTitle' => 'Edit Schedule',
            'pageSubTitle' => 'Update service availability',
            'icon' => 'edit',
            'schedule' => $schedule,
            'validation' => \Config\Services::validation()
        ];

        // Get services for dropdown
        // $data['services'] = $this->serviceModel->where('tenant_id', $tenantId)->findAll();
        
        // For now, use dummy data
        $data['services'] = [
            ['id' => 1, 'name' => 'Futsal Field A'],
            ['id' => 2, 'name' => 'Villa Anggrek'],
            ['id' => 3, 'name' => 'Haircut & Styling'],
        ];

        // Days of week for dropdown
        $data['days'] = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ];

        return view('schedule/edit', $data);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Fetch the schedule
        // $schedule = $this->scheduleModel->find($id);
        
        // if (!$schedule) {
        //     return redirect()->to('/schedule')->with('error', 'Schedule not found.');
        // }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'service_id' => 'required|numeric',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_duration' => 'required|numeric',
            'is_available' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $serviceId = $this->request->getPost('service_id');
        $day = $this->request->getPost('day');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');
        $slotDuration = $this->request->getPost('slot_duration');
        $isAvailable = $this->request->getPost('is_available');
        $userId = session()->get('userID');

        // Ensure service belongs to tenant
        // $service = $this->serviceModel->find($serviceId);
        // $tenantId = $this->getTenantId();
        
        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->back()->with('error', 'Service not found or you do not have permission to manage its schedule.');
        // }

        // Update schedule
        // $this->scheduleModel->update($id, [
        //     'service_id' => $serviceId,
        //     'day' => $day,
        //     'start_time' => $startTime,
        //     'end_time' => $endTime,
        //     'slot_duration' => $slotDuration,
        //     'is_available' => $isAvailable,
        //     'updated_by' => $userId,
        //     'updated_date' => date('Y-m-d H:i:s')
        // ]);

        return redirect()->to('/schedules?service_id=' . $serviceId)->with('success', 'Schedule updated successfully.');
    }

    public function special()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Special Schedule',
            'pageTitle' => 'Special Schedule',
            'pageSubTitle' => 'Define exceptions to regular schedule',
            'icon' => 'calendar-x',
            'validation' => \Config\Services::validation()
        ];

        // Get tenant ID
        $tenantId = $this->getTenantId();
        $roleId = session()->get('roleID');

        if (!$tenantId && $roleId != 1) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage schedules.');
        }

        // Get services for dropdown
        // $data['services'] = $this->serviceModel->where('tenant_id', $tenantId)->findAll();
        
        // For now, use dummy data
        $data['services'] = [
            ['id' => 1, 'name' => 'Futsal Field A'],
            ['id' => 2, 'name' => 'Villa Anggrek'],
            ['id' => 3, 'name' => 'Haircut & Styling'],
        ];

        // Get service special schedules
        $serviceId = $this->request->getGet('service_id');
        $month = $this->request->getGet('month') ?: date('m');
        $year = $this->request->getGet('year') ?: date('Y');
        
        // $data['specialDates'] = $this->scheduleModel->getServiceSpecialDates($serviceId, $month, $year);
        
        // For now, use dummy data
        $data['specialDates'] = [
            ['date' => '2025-06-01', 'is_closed' => true, 'note' => 'Public Holiday'],
            ['date' => '2025-06-15', 'is_closed' => true, 'note' => 'Maintenance Day'],
            ['date' => '2025-06-30', 'special_hours' => '10:00-16:00', 'note' => 'Early Closing']
        ];
        
        $data['currentMonth'] = $month;
        $data['currentYear'] = $year;
        $data['selectedServiceId'] = $serviceId;

        return view('schedule/special', $data);
    }

    public function storeSpecial()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'service_id' => 'required|numeric',
            'special_date' => 'required|valid_date',
            'is_closed' => 'permit_empty',
            'start_time' => 'permit_empty',
            'end_time' => 'permit_empty',
            'note' => 'permit_empty|max_length[255]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $serviceId = $this->request->getPost('service_id');
        $specialDate = $this->request->getPost('special_date');
        $isClosed = $this->request->getPost('is_closed') ? 1 : 0;
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');
        $note = $this->request->getPost('note');
        $userId = session()->get('userID');

        // Ensure service belongs to tenant
        // $service = $this->serviceModel->find($serviceId);
        // $tenantId = $this->getTenantId();
        
        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->back()->with('error', 'Service not found or you do not have permission to manage its schedule.');
        // }

        // Check if special date already exists
        // $existing = $this->scheduleSpecialModel
        //     ->where('service_id', $serviceId)
        //     ->where('date', $specialDate)
        //     ->first();
        
        // if ($existing) {
        //     // Update existing special date
        //     $this->scheduleSpecialModel->update($existing['id'], [
        //         'is_closed' => $isClosed,
        //         'start_time' => $startTime,
        //         'end_time' => $endTime,
        //         'note' => $note,
        //         'updated_by' => $userId,
        //         'updated_date' => date('Y-m-d H:i:s')
        //     ]);
        // } else {
        //     // Insert new special date
        //     $this->scheduleSpecialModel->insert([
        //         'service_id' => $serviceId,
        //         'date' => $specialDate,
        //         'is_closed' => $isClosed,
        //         'start_time' => $startTime,
        //         'end_time' => $endTime,
        //         'note' => $note,
        //         'created_by' => $userId,
        //         'created_date' => date('Y-m-d H:i:s')
        //     ]);
        // }

        return redirect()->to('/schedules/special?service_id=' . $serviceId)->with('success', 'Special schedule added successfully.');
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
}
