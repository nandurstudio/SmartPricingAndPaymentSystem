<?php

namespace App\Controllers;

use App\Models\ParameterModel;

class Parameter extends BaseController
{
    public function index()
    {
        $model = new ParameterModel();
        $data['parameters'] = $model->findAll();

        // Ubah status bitActive menjadi teks
        foreach ($data['parameters'] as &$parameter) {
            $parameter['bitActiveText'] = $parameter['bitActive'] ? 'Aktif' : 'Tidak Aktif';
        }

        return view('parameter/index', $data);
    }

    public function view($id)
    {
        $model = new ParameterModel();
        $data['parameter'] = $model->find($id);

        return view('parameter/view', $data);
    }

    public function create()
    {
        return view('parameter/create');
    }

    // Controller: store
    public function store()
    {
        $model = new ParameterModel();

        // Ambil txtNick dari session
        $txtNick = session()->get('userNick'); // Pastikan ini sesuai dengan data yang di-set di session

        $data = [
            'txtParameterName' => $this->request->getPost('txtParameterName'),
            'txtParameterDesc' => $this->request->getPost('txtParameterDesc'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtInsertedBy' => $txtNick,  // Ambil dari session
            'txtUpdatedBy' => $txtNick,  // Ambil dari session
            'txtGUID' => uniqid('', true)
        ];

        $model->insert($data);
        return redirect()->to('/parameter');
    }

    // Controller: update
    public function update($id)
    {
        $model = new ParameterModel();

        // Ambil txtNick dari session
        $txtNick = session()->get('userNick'); // Pastikan ini sesuai dengan data yang di-set di session

        $data = [
            'txtParameterName' => $this->request->getPost('txtParameterName'),
            'txtParameterDesc' => $this->request->getPost('txtParameterDesc'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => $txtNick, // Set sesuai user yang login
            'dtmUpdatedDate' => date('Y-m-d H:i:s'), // Ini akan disesuaikan dengan timezone
        ];

        $model->update($id, $data);
        return redirect()->to('/parameter');
    }

    public function edit($id)
    {
        $model = new ParameterModel();
        $data['parameter'] = $model->find($id);
        return view('parameter/edit', $data);
    }

    public function delete($id)
    {
        $model = new ParameterModel();
        $model->delete($id);
        return redirect()->to('/parameter');
    }
}
