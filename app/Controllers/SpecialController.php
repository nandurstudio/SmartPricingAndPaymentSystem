<?php

namespace App\Controllers;

class SpecialController extends BaseController
{
    protected $serviceModel;
    protected $specialScheduleModel;

    public function __construct()
    {
        helper(['form', 'url', 'date']);
        $this->serviceModel = new \App\Models\ServiceModel();
        $this->specialScheduleModel = new \App\Models\SpecialScheduleModel();
    }

    public function special()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $tenantId = $this->getTenantId();
        $serviceId = $this->request->getGet('service_id');
        $roleId = session()->get('roleID');
        $data['services'] = [];
        $data['serviceWarning'] = null;

        // Coba cari service dengan tenant
        if ($serviceId) {
            $service = $this->serviceModel->where('intServiceID', $serviceId)
                ->where('intTenantID', $tenantId)
                ->where('bitActive', 1)
                ->first();
            if ($service) {
                $data['services'] = [[
                    'id' => (int)$service['intServiceID'],
                    'name' => $service['txtName']
                ]];
            } else {
                // Fallback: cari service tanpa filter tenant
                $service = $this->serviceModel->where('intServiceID', $serviceId)
                    ->where('bitActive', 1)
                    ->first();
                if ($service) {
                    $data['services'] = [[
                        'id' => (int)$service['intServiceID'],
                        'name' => $service['txtName']
                    ]];
                    $data['serviceWarning'] = 'Service ditemukan, tapi tenant ID tidak cocok!';
                }
            }
        }
        if (empty($data['services'])) {
            $allServices = $this->serviceModel->where('intTenantID', $tenantId)
                ->where('bitActive', 1)
                ->findAll();
            $data['services'] = array_map(function($svc) {
                return [
                    'id' => (int)$svc['intServiceID'],
                    'name' => $svc['txtName']
                ];
            }, $allServices);
        }
        $model = $this->specialScheduleModel;
        $data['specialDates'] = [];
        if ($serviceId && is_numeric($serviceId)) {
            $specials = $model->where('intServiceID', $serviceId)
                ->where('bitActive', 1)
                ->orderBy('dtmSpecialDate', 'ASC')
                ->findAll();
        } else {
            $serviceIds = array_column($data['services'], 'id');
            $specials = !empty($serviceIds) ? 
                $model->whereIn('intServiceID', $serviceIds)
                    ->where('bitActive', 1)
                    ->orderBy('dtmSpecialDate', 'ASC')
                    ->findAll() : [];
        }
        if (!empty($specials)) {
            $serviceNames = array_column($data['services'], 'name', 'id');
            $serviceModel = $this->serviceModel;
            $data['specialDates'] = array_map(function($row) use ($serviceNames, $serviceModel) {
                $serviceId = (int)$row['intServiceID'];
                $serviceName = $serviceNames[$serviceId] ?? null;
                if ($serviceName === null) {
                    $svc = $serviceModel->find($serviceId);
                    $serviceName = $svc ? $svc['txtName'] : '';
                }
                return [
                    'id' => $row['intSpecialScheduleID'],
                    'service_id' => $serviceId,
                    'service_name' => $serviceName,
                    'date' => $row['dtmSpecialDate'],
                    'is_closed' => (bool)$row['bitIsClosed'],
                    'start_time' => $row['dtmStartTime'],
                    'end_time' => $row['dtmEndTime'],
                    'notes' => $row['txtNote']
                ];
            }, $specials);
        }
        $data['selectedServiceId'] = $serviceId;
        $data['pageTitle'] = 'Special Schedule';
        if (empty($data['services'])) {
            $data['serviceNotFound'] = true;
        }
        return view('schedules/special', $data);
    }

    public function storeSpecial()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }
        $startTime = $this->request->getPost('dtmStartTime');
        $endTime = $this->request->getPost('dtmEndTime');
        if (!$startTime) $startTime = '09:00';
        if (!$endTime) $endTime = '17:00';
        $data = [
            'intServiceID'      => $this->request->getPost('intServiceID'),
            'dtmSpecialDate'    => $this->request->getPost('dtmSpecialDate'),
            'dtmStartTime'      => $startTime,
            'dtmEndTime'        => $endTime,
            'bitIsClosed'       => $this->request->getPost('bitIsClosed') ? 1 : 0,
            'intSlotDuration'   => $this->request->getPost('intSlotDuration') ?? 60,
            'txtNote'           => $this->request->getPost('txtNote'),
            'txtGUID'           => uniqid('special_', true),
            'bitActive'         => 1,
            'txtCreatedBy'      => session()->get('userName') ?? 'system',
            'dtmCreatedDate'    => date('Y-m-d H:i:s'),
            'txtUpdatedBy'      => session()->get('userName') ?? 'system',
            'dtmUpdatedDate'    => date('Y-m-d H:i:s')
        ];
        $model = $this->specialScheduleModel;
        $model->insert($data);
        return redirect()->to('/schedules/special?service_id=' . $data['intServiceID'])->with('success', 'Special schedule saved.');
    }

    public function updateSpecial()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }
        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->back()->with('error', 'Invalid special schedule ID.');
        }
        $model = $this->specialScheduleModel;
        $special = $model->find($id);
        if (!$special) {
            return redirect()->back()->with('error', 'Special schedule not found.');
        }
        $startTime = $this->request->getPost('dtmStartTime');
        $endTime = $this->request->getPost('dtmEndTime');
        if (!$startTime) $startTime = '09:00';
        if (!$endTime) $endTime = '17:00';
        $data = [
            'intServiceID'      => $this->request->getPost('intServiceID'),
            'dtmSpecialDate'    => $this->request->getPost('dtmSpecialDate') ?? $this->request->getPost('dtmDate'),
            'dtmStartTime'      => $startTime,
            'dtmEndTime'        => $endTime,
            'bitIsClosed'       => $this->request->getPost('bitIsClosed') ? 1 : 0,
            'intSlotDuration'   => $this->request->getPost('intSlotDuration') ?? 60,
            'txtNote'           => $this->request->getPost('txtNote'),
            'bitActive'         => 1,
            'txtUpdatedBy'      => session()->get('userName') ?? 'system',
            'dtmUpdatedDate'    => date('Y-m-d H:i:s')
        ];
        $model->update($id, $data);
        return redirect()->to('/schedules/special?service_id=' . $data['intServiceID'])->with('success', 'Special schedule updated.');
    }

    public function apiServiceName()
    {
        $serviceId = $this->request->getGet('id');
        $service = $this->serviceModel->where('intServiceID', $serviceId)->first();
        if ($service) {
            return $this->response->setJSON([
                'success' => true,
                'id' => $service['intServiceID'],
                'name' => $service['txtName']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service not found'
            ]);
        }
    }

    /**
     * Get the tenant ID for the current user
     */
    private function getTenantId()
    {
        if (session()->get('roleID') == 1) {
            return 1;
        }
        $userId = session()->get('userID');
        return 1;
    }
}
