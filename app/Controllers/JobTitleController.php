<?php

namespace App\Controllers;

use App\Models\JobTitleModel;
use CodeIgniter\Controller;

class JobTitleController extends Controller
{
    public function index()
    {
        $jobTitleModel = new JobTitleModel();
        $data['jobTitles'] = $jobTitleModel->findAll();
        // Ubah status bitActive menjadi teks
        foreach ($data['jobTitles'] as &$jobTitle) {
            $jobTitle['bitActiveText'] = $jobTitle['bitActive'] ? 'Aktif' : 'Tidak Aktif';
        }

        // Halaman Job Title list
        return view('job_title/index', $data);
    }

    public function create()
    {
        return view('job_title/create');
    }

    public function store()
    {
        $jobTitleModel = new JobTitleModel();
        // Ambil txtNick dari session
        $txtNick = session()->get('userNick'); // Pastikan ini sesuai dengan data yang di-set di session
        // Ambil nilai bitActive dari form
        $bitActive = $this->request->getPost('bitActive') ? 1 : 0;

        $data = [
            'bitActive' => $bitActive, // Simpan status bitActive
            'txtJobTitle' => $this->request->getPost('txtJobTitle'),
            'txtGUID' => uniqid(), // Atur GUID
            'txtInsertedBy' => $txtNick, // Atur username, bisa diambil dari session
            'txtJobTitle' => ucwords($this->request->getPost('txtJobTitle')),
        ];

        $jobTitleModel->save($data);
        return redirect()->to('/job_title');
    }

    public function edit($id)
    {
        $jobTitleModel = new JobTitleModel();
        $data['jobTitle'] = $jobTitleModel->find($id);

        return view('job_title/edit', $data);
    }

    public function update($id)
    {
        $jobTitleModel = new JobTitleModel();

        // Ambil txtNick dari session
        $txtNick = session()->get('userNick'); // Pastikan ini sesuai dengan data yang di-set di session

        // Ambil nilai bitActive dari form
        $bitActive = $this->request->getPost('bitActive') ? 1 : 0;

        $data = [
            'bitActive' => $bitActive, // Simpan status bitActive
            'txtJobTitle' => $this->request->getPost('txtJobTitle'),
            'txtUpdatedBy' => $txtNick, // Atur username, bisa diambil dari session
        ];

        $jobTitleModel->update($id, $data);
        return redirect()->to('/job_title');
    }

    public function view($id)
    {
        $jobTitleModel = new JobTitleModel();
        $data['jobTitle'] = $jobTitleModel->find($id);

        return view('job_title/view', $data);
    }
}
