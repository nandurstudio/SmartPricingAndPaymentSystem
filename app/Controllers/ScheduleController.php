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
        }        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'intServiceID' => 'required|numeric',
            'txtDay' => 'required',
            'dtmStartTime' => 'required',
            'dtmEndTime' => 'required',
            'intSlotDuration' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }        $serviceId = $this->request->getPost('intServiceID');
        $day = $this->request->getPost('txtDay');
        $startTime = $this->request->getPost('dtmStartTime');
        $endTime = $this->request->getPost('dtmEndTime');
        $slotDuration = $this->request->getPost('intSlotDuration');
        $isAvailable = $this->request->getPost('bitIsAvailable');
        $userId = session()->get('userID');

        // Ensure service belongs to tenant
        // $service = $this->serviceModel->find($serviceId);
        // $tenantId = $this->getTenantId();
        
        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->back()->with('error', 'Service not found or you do not have permission to manage its schedule.');
        // }        // Check if schedule already exists
        $existingSchedule = $this->scheduleModel
            ->where('intServiceID', $serviceId)
            ->where('txtDay', $day)
            ->first();
        
        if ($existingSchedule) {
            // Update existing schedule
            $this->scheduleModel->update($existingSchedule['intScheduleID'], [
                'dtmStartTime' => $startTime,
                'dtmEndTime' => $endTime,
                'intSlotDuration' => $slotDuration,
                'bitIsAvailable' => $isAvailable,
                'txtUpdatedBy' => $userId,
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Insert new schedule
            $this->scheduleModel->insert([
                'intServiceID' => $serviceId,
                'txtDay' => $day,
                'dtmStartTime' => $startTime,
                'dtmEndTime' => $endTime,
                'intSlotDuration' => $slotDuration,
                'bitIsAvailable' => $isAvailable,
                'txtCreatedBy' => $userId,
                'dtmCreatedDate' => date('Y-m-d H:i:s'),
                'txtUpdatedBy' => $userId,
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('/schedules?service_id=' . $serviceId)->with('success', 'Schedule created successfully.');
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }        // Fetch schedule data with service info
        $schedule = $this->scheduleModel->getScheduleWithService($id);
        
        // If not found, redirect back with error
        if (!$schedule) {
            return redirect()->to('/schedules')->with('error', 'Schedule not found.');
        }

        // Get tenant ID
        $tenantId = $this->getTenantId();

        $data = [
            'title' => 'Edit Schedule',
            'pageTitle' => 'Edit Schedule',
            'pageSubTitle' => 'Update service availability',
            'icon' => 'edit',
            'schedule' => $schedule,
            'validation' => \Config\Services::validation()
        ];

        // Get services for dropdown
        $data['services'] = $this->serviceModel->where('intTenantID', $tenantId)
            ->where('bitActive', 1)
            ->findAll();

        return view('schedules/edit', $data);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Fetch the schedule
        $schedule = $this->scheduleModel->find($id);
        
        if (!$schedule) {
            return redirect()->to('/schedules')->with('error', 'Schedule not found.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'intServiceID' => 'required|numeric',
            'txtDay' => 'required',
            'dtmStartTime' => 'required',
            'dtmEndTime' => 'required',
            'intSlotDuration' => 'required|numeric',
            'bitIsAvailable' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $serviceId = $this->request->getPost('intServiceID');
        $day = $this->request->getPost('txtDay');
        $startTime = $this->request->getPost('dtmStartTime');
        $endTime = $this->request->getPost('dtmEndTime');
        $slotDuration = $this->request->getPost('intSlotDuration');
        $isAvailable = $this->request->getPost('bitIsAvailable');
        $userId = session()->get('userID');

        // Update schedule
        $this->scheduleModel->update($id, [
            'intServiceID' => $serviceId,
            'txtDay' => $day,
            'dtmStartTime' => $startTime,
            'dtmEndTime' => $endTime,
            'intSlotDuration' => $slotDuration,
            'bitIsAvailable' => $isAvailable,
            'txtUpdatedBy' => $userId,
            'dtmUpdatedDate' => date('Y-m-d H:i:s')
        ]);

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
            [
                'id' => 1,
                'service_id' => 1,
                'service_name' => 'Futsal Field A',
                'date' => '2025-06-01',
                'is_closed' => true,
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Public Holiday'
            ],
            [
                'id' => 2,
                'service_id' => 1,
                'service_name' => 'Futsal Field A',
                'date' => '2025-06-15',
                'is_closed' => true,
                'start_time' => null,
                'end_time' => null,
                'notes' => 'Maintenance Day'
            ],
            [
                'id' => 3,
                'service_id' => 1,
                'service_name' => 'Futsal Field A',
                'date' => '2025-06-30',
                'is_closed' => false,
                'start_time' => '10:00',
                'end_time' => '16:00',
                'notes' => 'Early Closing'
            ]
        ];
        
        $data['currentMonth'] = $month;
        $data['currentYear'] = $year;
        $data['selectedServiceId'] = $serviceId;

        return view('schedules/special', $data);
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
            'intServiceID' => 'required|numeric',
            'dtmDate' => 'required|valid_date',
            'bitIsClosed' => 'permit_empty',
            'dtmStartTime' => 'permit_empty',
            'dtmEndTime' => 'permit_empty',
            'txtNote' => 'permit_empty|max_length[255]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $serviceId = $this->request->getPost('intServiceID');
        $specialDate = $this->request->getPost('dtmDate');
        $isClosed = $this->request->getPost('bitIsClosed') ? 1 : 0;
        $startTime = $this->request->getPost('dtmStartTime');
        $endTime = $this->request->getPost('dtmEndTime');
        $note = $this->request->getPost('txtNote');
        $userId = session()->get('userID');

        // Check if special date already exists
        $existing = $this->scheduleModel->getSpecialSchedule($serviceId, $specialDate);
        
        if ($existing) {
            // Update existing special date
            $this->scheduleModel->update($existing['intScheduleID'], [
                'bitIsClosed' => $isClosed,
                'dtmStartTime' => $startTime,
                'dtmEndTime' => $endTime,
                'txtNote' => $note,
                'txtUpdatedBy' => $userId,
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Insert new special date
            $this->scheduleModel->insert([
                'intServiceID' => $serviceId,
                'dtmDate' => $specialDate,
                'bitIsClosed' => $isClosed,
                'dtmStartTime' => $startTime,
                'dtmEndTime' => $endTime,
                'txtNote' => $note,
                'txtCreatedBy' => $userId,
                'dtmCreatedDate' => date('Y-m-d H:i:s')
            ]);
        }

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
