<?php

namespace App\Controllers;

class ScheduleController extends BaseController
{    protected $scheduleModel;
    protected $serviceModel;
    protected $bookingModel;
    protected $specialScheduleModel;

    public function __construct()
    {
        helper(['form', 'url', 'date']);
        $this->scheduleModel = new \App\Models\ScheduleModel();
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->bookingModel = new \App\Models\BookingModel();
        $this->specialScheduleModel = new \App\Models\SpecialScheduleModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get tenant ID
        $serviceId = $this->request->getGet('service_id');
        $tenantId = null;
        $services = [];
        $roleId = session()->get('roleID');

        if ($serviceId) {
            // Cari service dan tenant-nya
            $service = $this->serviceModel->find($serviceId);
            if ($service) {
                $tenantId = $service['intTenantID'];
                $services = [$service];
                // Ambil nama tenant
                $tenantModel = new \App\Models\MTenantModel();
                $tenant = $tenantModel->find($tenantId);
                $tenant_name = $tenant['txtTenantName'] ?? 'Tenant';
            }
        }
        if (!$tenantId) {
            $tenantId = $this->getTenantId();
        }
        if (!$services) {
            $services = $this->serviceModel->where('intTenantID', $tenantId)
                ->where('bitActive', 1)
                ->findAll();
        }
        if (!$tenantId && $roleId != 1) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage schedules.');
        }

        $data = [
            'title' => 'Schedules',
            'pageTitle' => 'Schedule Management',
            'pageSubTitle' => 'View and manage service availability',
            'icon' => 'clock',
            'services' => $services
        ];

        // Get service schedules
        $data['schedules'] = $this->scheduleModel->getServiceSchedules($tenantId, $serviceId);
        $data['selectedServiceId'] = $serviceId;

        return view('schedules/index', $data);
    }

    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $serviceId = $this->request->getGet('service_id');
        $tenantId = null;
        $services = [];
        $roleId = session()->get('roleID');

        if ($serviceId) {
            // Cari service dan tenant-nya
            $service = $this->serviceModel->find($serviceId);
            if ($service) {
                $tenantId = $service['intTenantID'];
                $services = [$service];
                // Ambil nama tenant
                $tenantModel = new \App\Models\MTenantModel();
                $tenant = $tenantModel->find($tenantId);
                $tenant_name = $tenant['txtTenantName'] ?? 'Tenant';
            }
        }
        if (!$tenantId) {
            $tenantId = $this->getTenantId();
        }
        if (!$services) {
            $services = $this->serviceModel->where('intTenantID', $tenantId)
                ->where('bitActive', 1)
                ->findAll();
        }
        if (!$tenantId && $roleId != 1) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenants/create')->with('info', 'Please create a tenant first to manage schedules.');
        }

        $data = [
            'title' => 'Create Schedule',
            'pageTitle' => 'Create New Schedule',
            'pageSubTitle' => 'Define service availability',
            'icon' => 'plus-circle',
            'validation' => \Config\Services::validation(),
            'services' => $services,
            'tenant_name' => $tenant_name ?? null
        ];

        // Days of week for dropdown
        $data['days'] = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ];

        // Ambil service sesuai parameter jika ada, jika tidak semua
        $serviceId = $this->request->getGet('service_id');
        if ($serviceId) {
            $service = $this->serviceModel->where('intServiceID', $serviceId)
                ->where('bitActive', 1)
                ->first();
            if ($service) {
                $data['services'] = [$service];
                $data['serviceName'] = $service['txtName'];
            } else {
                $data['services'] = [];
                $data['serviceName'] = null;
            }
        } else {
            $allServices = $this->serviceModel->where('intTenantID', $tenantId)->where('bitActive', 1)->findAll();
            $data['services'] = array_map(function($svc) {
                return [
                    'id' => $svc['intServiceID'],
                    'name' => $svc['txtName']
                ];
            }, $allServices);
            $data['serviceName'] = null;
        }

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
        $isActive = $this->request->getPost('bitActive', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        // $guid = $this->request->getPost('txtGUID') ?: $this->generateGUID(); // Tidak perlu ambil dari form
        $userId = session()->get('userID');
        // Fitur repeat weekly dihapus, tidak ada logic repeat mingguan

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
            $this->scheduleModel->update($existingSchedule['intScheduleID'], [
                'dtmStartTime' => $startTime,
                'dtmEndTime' => $endTime,
                'intSlotDuration' => $slotDuration,
                'bitIsAvailable' => $isAvailable,
                'bitActive' => $isActive,
                // 'txtGUID' tidak diubah saat update
                'txtUpdatedBy' => session()->get('userName'),
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->scheduleModel->insert([
                'intServiceID' => $serviceId,
                'txtDay' => $day,
                'dtmStartTime' => $startTime,
                'dtmEndTime' => $endTime,
                'intSlotDuration' => $slotDuration,
                'bitIsAvailable' => $isAvailable,
                'bitActive' => $isActive,
                'txtGUID' => $this->generateGUID(), // generate hanya saat create
                'txtCreatedBy' => session()->get('userName'),
                'dtmCreatedDate' => date('Y-m-d H:i:s'),
                'txtUpdatedBy' => session()->get('userName'),
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
        $isActive = $this->request->getPost('bitActive', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $guid = $this->request->getPost('txtGUID') ?: $schedule['txtGUID'];
        $userId = session()->get('userID');

        // Update schedule
        $this->scheduleModel->update($id, [
            'intServiceID' => $serviceId,
            'txtDay' => $day,
            'dtmStartTime' => $startTime,
            'dtmEndTime' => $endTime,
            'intSlotDuration' => $slotDuration,
            'bitIsAvailable' => $isAvailable,
            'bitActive' => $isActive,
            // 'txtGUID' tidak diubah saat update
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/schedules?service_id=' . $serviceId)->with('success', 'Schedule updated successfully.');
    }

    public function delete()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }
        $id = $this->request->getPost('id');
        $repeatDelete = $this->request->getPost('repeatDelete');
        if (!$id) {
            return redirect()->back()->with('error', 'No schedule ID provided.');
        }
        $schedule = $this->scheduleModel->find($id);
        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }
        if ($repeatDelete) {
            // Hapus semua jadwal dengan service, hari, jam mulai, jam akhir yang sama
            $this->scheduleModel->where('intServiceID', $schedule['intServiceID'])
                ->where('txtDay', $schedule['txtDay'])
                ->where('dtmStartTime', $schedule['dtmStartTime'])
                ->where('dtmEndTime', $schedule['dtmEndTime'])
                ->delete();
            return redirect()->to('/schedules?service_id=' . $schedule['intServiceID'])->with('success', 'All repeated schedules deleted.');
        } else {
            $this->scheduleModel->delete($id);
            return redirect()->to('/schedules?service_id=' . $schedule['intServiceID'])->with('success', 'Schedule deleted successfully.');
        }
    }

    // Special schedule methods have been moved to SpecialController

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

    // Tambahkan fungsi generateGUID
    private function generateGUID()
    {
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
