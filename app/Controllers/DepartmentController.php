<?php

namespace App\Controllers;

use App\Models\DepartmentModel;
use CodeIgniter\RESTful\ResourceController;

class DepartmentController extends ResourceController
{
    protected $modelName = 'App\Models\DepartmentModel';
    protected $format = 'json';

    public function getDepartments()
    {
        $request = service('request');
        $model = new \App\Models\DepartmentModel();

        // DataTable parameters
        $start = $request->getVar('start');
        $length = $request->getVar('length');
        $search = $request->getVar('search')['value'];

        // Query total data
        $totalData = $model->countAll();

        // Query data filtered
        $query = $model;
        if (!empty($search)) {
            $query = $query->like('txtDepartmentName', $search);
        }
        $totalFiltered = $query->countAllResults(false);

        // Fetch data
        $data = $query->orderBy('intDepartmentID', 'ASC')
        ->findAll($length, $start);

        // Return response
        return $this->response->setJSON([
            'draw' => intval($request->getVar('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
}
