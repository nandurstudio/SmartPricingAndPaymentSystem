<?php

namespace App\Controllers;

use App\Models\ServiceTypeModel;
use App\Models\MenuModel;

class ServiceTypeController extends BaseController
{
    protected $serviceTypeModel;
    protected $menuModel;

    public function __construct()
    {
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);

        // Order by category and name
        $this->serviceTypeModel->orderBy('txtCategory', 'ASC')->orderBy('txtName', 'ASC');

        $data = [
            'title' => 'Service Types',
            'serviceTypes' => $this->serviceTypeModel->findAll(),
            'menus' => $menus,
            'pageTitle' => 'Service Types',
            'pageSubTitle' => 'Manage service types',
            'icon' => 'list'
        ];

        return view('service-type/index', $data);
    }

    public function toggleStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $serviceType = $this->serviceTypeModel->find($id);
        if (!$serviceType) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Service Type not found']);
        }

        try {
            $this->serviceTypeModel->update($id, [
                'bitActive' => !$serviceType['bitActive'],
                'txtUpdatedBy' => session()->get('userName'),
                'dtmUpdatedDate' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service Type status updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Failed to update service type status'
            ]);
        }
    }

    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);        return view('service-type/create', [
            'title' => 'Create Service Type',
            'menus' => $menus,
            'pageTitle' => 'Create New Service Type',
            'serviceType' => [
                'txtName' => '',
                'txtDescription' => '',
                'bitActive' => 1
            ]
        ]);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $rules = [
            'txtName' => 'required|min_length[3]|max_length[255]',
            'txtDescription' => 'permit_empty|max_length[1000]',
            'txtIcon' => 'permit_empty|max_length[255]',
            'txtCategory' => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }        $data = [
            'txtGUID' => uniqid('svc_', true),
            'txtName' => $this->request->getPost('txtName'),
            'txtSlug' => url_title($this->request->getPost('txtName'), '-', true),
            'txtDescription' => $this->request->getPost('txtDescription'),
            'txtIcon' => $this->request->getPost('txtIcon'),
            'txtCategory' => $this->request->getPost('txtCategory'),
            'bitIsSystem' => 0,
            'bitIsApproved' => 1,
            'intRequestedBy' => session()->get('userID'),
            'intApprovedBy' => session()->get('userID'),
            'dtmApprovedDate' => date('Y-m-d H:i:s'),
            'jsonDefaultAttributes' => null,
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtCreatedBy' => session()->get('userName'),
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ];

        try {
            $this->serviceTypeModel->insert($data);
            return redirect()->to('/service-types')
                ->with('message', 'Service Type created successfully')
                ->with('message_type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create service type: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $serviceType = $this->serviceTypeModel->find($id);
        if (!$serviceType) {
            return redirect()->to('/service-types')->with('error', 'Service Type not found');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);        return view('service-type/edit', [
            'title' => 'Edit Service Type',
            'menus' => $menus,
            'pageTitle' => 'Edit Service Type',
            'serviceType' => $serviceType
        ]);
    }

    public function update($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $serviceType = $this->serviceTypeModel->find($id);
        if (!$serviceType) {
            return redirect()->to('/service-types')->with('error', 'Service Type not found');
        }

        $rules = [
            'txtName' => 'required|min_length[3]|max_length[255]',
            'txtDescription' => 'permit_empty|max_length[1000]',
            'txtIcon' => 'permit_empty|max_length[255]',
            'txtCategory' => 'permit_empty|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }        $data = [
            'txtName' => $this->request->getPost('txtName'),
            'txtSlug' => url_title($this->request->getPost('txtName'), '-', true),
            'txtDescription' => $this->request->getPost('txtDescription'),
            'txtIcon' => $this->request->getPost('txtIcon'),
            'txtCategory' => $this->request->getPost('txtCategory'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s')
        ];

        try {
            $this->serviceTypeModel->update($id, $data);
            return redirect()->to('/service-types')
                ->with('message', 'Service Type updated successfully')
                ->with('message_type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update service type: ' . $e->getMessage());
        }
    }
}
