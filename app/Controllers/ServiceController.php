<?php

namespace App\Controllers;

class ServiceController extends BaseController
{
    protected $serviceModel;
    protected $serviceTypeModel;
    protected $serviceAttributeModel;

    public function __construct()
    {
        helper(['form', 'url']);
        // Note: We'll need to create these models
        // $this->serviceModel = new \App\Models\ServiceModel();
        // $this->serviceTypeModel = new \App\Models\ServiceTypeModel();
        // $this->serviceAttributeModel = new \App\Models\ServiceAttributeModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $data = [
            'title' => 'Services',
            'pageTitle' => 'Services Management',
            'pageSubTitle' => 'Create and manage your booking services',
            'icon' => 'briefcase'
        ];

        // Get tenant ID - in a multi-tenant app, we need to filter by tenant
        $tenantId = $this->getTenantId();

        if (!$tenantId) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenant/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Get services for this tenant
        // $data['services'] = $this->serviceModel->where('tenant_id', $tenantId)->findAll();

        // For now, just return dummy data until we create the models
        $data['services'] = [
            [
                'id' => 1,
                'name' => 'Futsal Field A',
                'type' => 'Futsal',
                'price' => 150000,
                'duration' => '60',
                'is_active' => 1
            ],
            [
                'id' => 2,
                'name' => 'Villa Anggrek',
                'type' => 'Villa',
                'price' => 1200000,
                'duration' => '1440', // 24 hours in minutes
                'is_active' => 1
            ],
            [
                'id' => 3,
                'name' => 'Haircut & Styling',
                'type' => 'Salon',
                'price' => 75000,
                'duration' => '45',
                'is_active' => 1
            ]
        ];

        return view('service/index', $data);
    }

    public function create()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get tenant ID
        $tenantId = $this->getTenantId();

        if (!$tenantId) {
            // No tenant assigned to user, redirect to tenant creation
            return redirect()->to('/tenant/create')->with('info', 'Please create a tenant first to manage services.');
        }

        $data = [
            'title' => 'Create Service',
            'pageTitle' => 'Create New Service',
            'pageSubTitle' => 'Add a new service to your booking system',
            'icon' => 'plus-circle',
            'validation' => \Config\Services::validation()
        ];

        // Get service types for dropdown
        // $data['serviceTypes'] = $this->serviceTypeModel->findAll();
        
        // For now, just return dummy data
        $data['serviceTypes'] = [
            ['id' => 1, 'name' => 'Futsal'],
            ['id' => 2, 'name' => 'Villa'],
            ['id' => 3, 'name' => 'Salon'],
            ['id' => 4, 'name' => 'Workspace'],
            ['id' => 5, 'name' => 'Restaurant'],
            ['id' => 6, 'name' => 'Course'],
        ];

        return view('service/create', $data);
    }

    public function store()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get tenant ID
        $tenantId = $this->getTenantId();

        if (!$tenantId) {
            // No tenant assigned to user
            return redirect()->to('/tenant/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'type_id' => 'required|numeric',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'description' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data for service creation
        $userId = session()->get('userID');
        $data = [
            'name' => $this->request->getPost('name'),
            'service_type_id' => $this->request->getPost('type_id'),
            'price' => $this->request->getPost('price'),
            'duration' => $this->request->getPost('duration'),
            'description' => $this->request->getPost('description'),
            'tenant_id' => $tenantId,
            'is_active' => 1,
            'created_by' => $userId,
            'created_date' => date('Y-m-d H:i:s')
        ];

        // Insert service data
        // $this->serviceModel->insert($data);

        // Process custom attributes
        // $typeId = $this->request->getPost('type_id');
        // $attributes = $this->serviceAttributeModel->where('service_type_id', $typeId)->findAll();
        
        // foreach ($attributes as $attr) {
        //     $attributeValue = $this->request->getPost('attribute_' . $attr['id']);
        //     if ($attributeValue) {
        //         $this->serviceAttributeValueModel->insert([
        //             'service_id' => $serviceId,
        //             'attribute_id' => $attr['id'],
        //             'value' => $attributeValue,
        //             'created_by' => $userId,
        //             'created_date' => date('Y-m-d H:i:s')
        //         ]);
        //     }
        // }

        return redirect()->to('/service')->with('success', 'Service created successfully.');
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get tenant ID
        $tenantId = $this->getTenantId();
        
        if (!$tenantId) {
            // No tenant assigned to user
            return redirect()->to('/tenant/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Fetch the service and verify it belongs to the tenant
        // $service = $this->serviceModel->find($id);
        
        // For now, use dummy data
        $service = [
            'id' => $id,
            'name' => 'Futsal Field A',
            'service_type_id' => 1,
            'price' => 150000,
            'duration' => '60',
            'description' => 'Professional futsal field with complete facilities',
            'tenant_id' => $tenantId,
            'is_active' => 1
        ];

        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->to('/service')->with('error', 'Service not found or you do not have permission to edit it.');
        // }

        $data = [
            'title' => 'Edit Service',
            'pageTitle' => 'Edit Service',
            'pageSubTitle' => 'Update your service details',
            'icon' => 'edit',
            'service' => $service,
            'validation' => \Config\Services::validation()
        ];

        // Get service types for dropdown
        // $data['serviceTypes'] = $this->serviceTypeModel->findAll();
        
        // For now, use dummy data
        $data['serviceTypes'] = [
            ['id' => 1, 'name' => 'Futsal'],
            ['id' => 2, 'name' => 'Villa'],
            ['id' => 3, 'name' => 'Salon'],
            ['id' => 4, 'name' => 'Workspace'],
            ['id' => 5, 'name' => 'Restaurant'],
            ['id' => 6, 'name' => 'Course'],
        ];

        return view('service/edit', $data);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get tenant ID
        $tenantId = $this->getTenantId();

        if (!$tenantId) {
            // No tenant assigned to user
            return redirect()->to('/tenant/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Fetch the service and verify it belongs to the tenant
        // $service = $this->serviceModel->find($id);
        
        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->to('/service')->with('error', 'Service not found or you do not have permission to update it.');
        // }

        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'type_id' => 'required|numeric',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'description' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data for update
        $userId = session()->get('userID');
        $data = [
            'name' => $this->request->getPost('name'),
            'service_type_id' => $this->request->getPost('type_id'),
            'price' => $this->request->getPost('price'),
            'duration' => $this->request->getPost('duration'),
            'description' => $this->request->getPost('description'),
            'updated_by' => $userId,
            'updated_date' => date('Y-m-d H:i:s')
        ];

        // Update service
        // $this->serviceModel->update($id, $data);

        // Process custom attributes
        // $typeId = $this->request->getPost('type_id');
        // $attributes = $this->serviceAttributeModel->where('service_type_id', $typeId)->findAll();
        
        // foreach ($attributes as $attr) {
        //     $attributeValue = $this->request->getPost('attribute_' . $attr['id']);
        //     if ($attributeValue) {
        //         // Check if value exists
        //         $existingValue = $this->serviceAttributeValueModel
        //             ->where('service_id', $id)
        //             ->where('attribute_id', $attr['id'])
        //             ->first();
                
        //         if ($existingValue) {
        //             // Update
        //             $this->serviceAttributeValueModel->update($existingValue['id'], [
        //                 'value' => $attributeValue,
        //                 'updated_by' => $userId,
        //                 'updated_date' => date('Y-m-d H:i:s')
        //             ]);
        //         } else {
        //             // Insert
        //             $this->serviceAttributeValueModel->insert([
        //                 'service_id' => $id,
        //                 'attribute_id' => $attr['id'],
        //                 'value' => $attributeValue,
        //                 'created_by' => $userId,
        //                 'created_date' => date('Y-m-d H:i:s')
        //             ]);
        //         }
        //     }
        // }

        return redirect()->to('/service')->with('success', 'Service updated successfully.');
    }

    public function view($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Get tenant ID
        $tenantId = $this->getTenantId();

        if (!$tenantId) {
            // No tenant assigned to user
            return redirect()->to('/tenant/create')->with('info', 'Please create a tenant first to manage services.');
        }

        // Fetch the service and verify it belongs to the tenant
        // $service = $this->serviceModel->find($id);
        
        // For now, use dummy data
        $service = [
            'id' => $id,
            'name' => 'Futsal Field A',
            'type' => 'Futsal',
            'price' => 150000,
            'duration' => '60 minutes',
            'description' => 'Professional futsal field with complete facilities',
            'tenant_id' => $tenantId,
            'is_active' => 1
        ];
        
        // if (!$service || $service['tenant_id'] != $tenantId) {
        //     return redirect()->to('/service')->with('error', 'Service not found or you do not have permission to view it.');
        // }

        // Get custom attributes for this service
        // $serviceAttributes = $this->serviceAttributeValueModel->getServiceAttributes($id);

        $data = [
            'title' => 'Service Details',
            'pageTitle' => 'Service Details',
            'pageSubTitle' => 'View service information',
            'icon' => 'info-circle',
            'service' => $service,
            // 'serviceAttributes' => $serviceAttributes
        ];

        return view('service/view', $data);
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
