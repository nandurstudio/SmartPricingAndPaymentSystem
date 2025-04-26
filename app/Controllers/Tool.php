<?php

namespace App\Controllers;

use App\Models\ToolModel;

class Tool extends BaseController
{
    public function index()
    {
        $model = new ToolModel();
        $data['tools'] = $model->findAll();

        // Ubah status bitActive menjadi teks
        foreach ($data['tools'] as &$tool) {
            $tool['bitActiveText'] = $tool['bitActive'] ? 'Aktif' : 'Tidak Aktif';
        }

        return view('tool/index', $data);
    }

    public function view($id)
    {
        $model = new ToolModel();
        $data['tool'] = $model->find($id);

        return view('tool/view', $data);
    }

    public function create()
    {
        return view('tool/create');
    }

    // Controller: store
    public function store()
    {
        $model = new ToolModel();

        // Ambil txtNick dari session
        $txtNick = session()->get('userNick'); // Pastikan ini sesuai dengan data yang di-set di session

        $data = [
            'txtToolName' => $this->request->getPost('txtToolName'),
            'txtToolDesc' => $this->request->getPost('txtToolDesc'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtInsertedBy' => $txtNick,  // Ambil dari session
            'txtUpdatedBy' => $txtNick,  // Ambil dari session
            'txtGUID' => uniqid('', true)
        ];

        $model->insert($data);
        return redirect()->to('/tool');
    }

    // Controller: update
    public function update($id)
    {
        $model = new ToolModel();

        // Ambil txtNick dari session
        $txtNick = session()->get('userNick'); // Pastikan ini sesuai dengan data yang di-set di session

        $data = [
            'txtToolName' => $this->request->getPost('txtToolName'),
            'txtToolDesc' => $this->request->getPost('txtToolDesc'),
            'bitActive' => $this->request->getPost('bitActive') ? 1 : 0,
            'txtUpdatedBy' => $txtNick, // Set sesuai user yang login
            'dtmUpdatedDate' => date('Y-m-d H:i:s'), // Ini akan disesuaikan dengan timezone
        ];

        $model->update($id, $data);
        return redirect()->to('/tool');
    }

    public function edit($id)
    {
        $model = new ToolModel();
        $data['tool'] = $model->find($id);
        return view('tool/edit', $data);
    }

    public function delete($id)
    {
        $model = new ToolModel();
        $model->delete($id);
        return redirect()->to('/tool');
    }
}
