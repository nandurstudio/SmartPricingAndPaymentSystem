<?php

namespace App\Controllers;

use App\Models\LineModel;
use App\Models\MenuModel;

class LineController extends BaseController
{
    // INDEX - Menampilkan daftar user
    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');

        // Ganti MenuModel menjadi MenusModel
        $menusModel = new MenuModel();  // Menyesuaikan dengan model baru
        $menus = $menusModel->getMenusByRole($roleID);  // Memanggil method dari MenusModel

        // Ambil data lines dari model
        $linesModel = new LineModel();
        $lines = $linesModel->findAll();

        // Kirim data ke view
        return view('line/index', [
            'menus' => $menus,
            'lines' => $lines,
            'icon' => 'users',
            'pageSubTitle' => 'Menampilkan daftar Line', // Mengirimkan sub-judul ke view
            'cardTitle' => 'Line',
            'pageTitle' => 'Master Line' // Mengirimkan judul ke view
        ]);
    }

    public function create()
    {
        return view('line/create');
    }

    public function store()
    {
        $model = new LineModel();

        $data = [
            'txtLine' => $this->request->getPost('txtLine'),
            'txtDesc' => $this->request->getPost('txtDesc'),
            'txtInsertedBy' => 'system',
            'txtGUID' => uniqid(),
        ];

        $model->save($data);
        return redirect()->to('/line');
    }

    public function update($id)
    {
        $model = new LineModel();
        $session = session(); // Ambil session

        $data = [
            'txtLine' => $this->request->getPost('txtLine'),
            'txtDesc' => $this->request->getPost('txtDesc'),
            'bitActive' => $this->request->getPost('bitActive'),
            'txtUpdatedBy' => $session->get('userID'), // Ambil userID dari session
            'dtmUpdatedDate' => date('Y-m-d H:i:s'), // Set tanggal update
        ];

        $model->update($id, $data);
        return redirect()->to('/line');
    }


    public function edit($id)
    {
        $model = new LineModel();
        $data['line'] = $model->find($id);
        return view('line/edit', $data);
    }

    // app/Controllers/LineController.php

    public function detail($id)
    {
        $lineModel = new \App\Models\LineModel();
        $line = $lineModel->find($id);

        if (!$line) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Line with ID $id not found.");
        }

        return view('line/detail', ['line' => $line]);
    }
}
