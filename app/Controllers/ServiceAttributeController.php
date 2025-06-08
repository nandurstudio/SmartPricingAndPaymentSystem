<?php

namespace App\Controllers;

use App\Models\ServiceTypeModel;
use App\Models\MenuModel;

class ServiceAttributeController extends BaseController
{
    protected $serviceTypeModel;
    protected $menuModel;
    protected $db;

    public function __construct()
    {
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->menuModel = new MenuModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);

        // Get all service types with their attributes
        $serviceTypes = $this->serviceTypeModel->findAll();
        
        // Get attributes for each service type
        foreach ($serviceTypes as &$type) {
            $type['attributes'] = $this->db->table('m_service_type_attributes')
                ->where('intServiceTypeID', $type['intServiceTypeID'])
                ->orderBy('intDisplayOrder', 'ASC')
                ->get()
                ->getResultArray();
        }
        
        $data = [
            'title' => 'Service Attributes',
            'serviceTypes' => $serviceTypes,
            'menus' => $menus,
            'pageTitle' => 'Service Attributes',
            'pageSubTitle' => 'Manage service type attributes',
            'icon' => 'list'
        ];

        return view('service-attributes/index', $data);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);
        $serviceTypes = $this->serviceTypeModel->findAll();

        return view('service-attributes/create', [
            'title' => 'Create Attribute',
            'menus' => $menus,
            'pageTitle' => 'Create Service Attribute',
            'pageSubTitle' => 'Add a new attribute to a service type',
            'serviceTypes' => $serviceTypes,
            'icon' => 'plus-circle'
        ]);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $rules = [
            'intServiceTypeID' => 'required|numeric',
            'txtName' => 'required|min_length[3]|max_length[100]',
            'txtLabel' => 'required|min_length[3]|max_length[255]',
            'txtFieldType' => 'required|in_list[text,number,boolean,select,date,time,datetime]',
            'txtDefaultValue' => 'permit_empty|max_length[255]',
            'txtValidation' => 'permit_empty|max_length[1000]',
            'intDisplayOrder' => 'permit_empty|numeric',
            'bitRequired' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'txtGUID' => uniqid('attr_', true),
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtName' => $this->request->getPost('txtName'),
            'txtLabel' => $this->request->getPost('txtLabel'),
            'txtFieldType' => $this->request->getPost('txtFieldType'),
            'jsonOptions' => $this->request->getPost('jsonOptions'),
            'bitRequired' => $this->request->getPost('bitRequired') ? 1 : 0,
            'txtDefaultValue' => $this->request->getPost('txtDefaultValue'),
            'txtValidation' => $this->request->getPost('txtValidation'),
            'intDisplayOrder' => $this->request->getPost('intDisplayOrder') ?? 0,
            'bitActive' => 1,
            'txtCreatedBy' => session()->get('userName'),
            'dtmCreatedDate' => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('m_service_type_attributes')->insert($data);
            return redirect()->to('/service-attributes')
                ->with('message', 'Service attribute created successfully')
                ->with('message_type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create service attribute: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $attribute = $this->db->table('m_service_type_attributes')->where('intAttributeID', $id)->get()->getRow();
        
        if (!$attribute) {
            return redirect()->to('/service-attributes')
                ->with('error', 'Attribute not found');
        }

        $roleID = session()->get('roleID');
        $menus = $this->menuModel->getMenusByRole($roleID);
        $serviceTypes = $this->serviceTypeModel->findAll();

        return view('service-attributes/edit', [
            'title' => 'Edit Attribute',
            'menus' => $menus,
            'pageTitle' => 'Edit Service Attribute',
            'pageSubTitle' => 'Modify service type attribute',
            'attribute' => $attribute,
            'serviceTypes' => $serviceTypes,
            'icon' => 'edit'
        ]);
    }

    public function update($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $rules = [
            'intServiceTypeID' => 'required|numeric',
            'txtName' => 'required|min_length[3]|max_length[100]',
            'txtLabel' => 'required|min_length[3]|max_length[255]',
            'txtFieldType' => 'required|in_list[text,number,boolean,select,date,time,datetime]',
            'txtDefaultValue' => 'permit_empty|max_length[255]',
            'txtValidation' => 'permit_empty|max_length[1000]',
            'intDisplayOrder' => 'permit_empty|numeric',
            'bitRequired' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'intServiceTypeID' => $this->request->getPost('intServiceTypeID'),
            'txtName' => $this->request->getPost('txtName'),
            'txtLabel' => $this->request->getPost('txtLabel'),
            'txtFieldType' => $this->request->getPost('txtFieldType'),
            'jsonOptions' => $this->request->getPost('jsonOptions'),
            'bitRequired' => $this->request->getPost('bitRequired') ? 1 : 0,
            'txtDefaultValue' => $this->request->getPost('txtDefaultValue'),
            'txtValidation' => $this->request->getPost('txtValidation'),
            'intDisplayOrder' => $this->request->getPost('intDisplayOrder') ?? 0,
            'txtUpdatedBy' => session()->get('userName'),
            'dtmUpdatedDate' => date('Y-m-d H:i:s')
        ];

        try {
            $this->db->table('m_service_type_attributes')
                ->where('intAttributeID', $id)
                ->update($data);

            return redirect()->to('/service-attributes')
                ->with('message', 'Service attribute updated successfully')
                ->with('message_type', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update service attribute: ' . $e->getMessage());
        }
    }
}
