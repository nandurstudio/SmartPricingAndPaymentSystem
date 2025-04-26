<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\LineModel;
use App\Models\JobTitleModel;
use App\Models\UserJobTitleModel;


class Home extends BaseController
{
    protected $userJobTitleModel;

    public function __construct()
    {
        $this->userJobTitleModel = new UserJobTitleModel();
    }

    public function index()
    {
        $menuModel = new MenuModel();

        // Ambil role dari session, misal session role ID tersimpan sebagai 'role_id'
        $intRoleID = session()->get('roleID');

        // Ambil menu berdasarkan role
        $menus = $menuModel->getMenusByRole($intRoleID);

        // Debugging: Cek data menu
        // print_r($menus); // Tambahkan ini
        // exit(); // Berhenti sementara untuk melihat hasil output

        return view('dashboard', [
            'menus' => $menus,
            'title' => 'Dashboard'
        ]);
    }

    public function auto_suggest()
    {
        // Redirect jika belum login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $roleID = session()->get('roleID');

        // Ambil menu berdasarkan role
        $menusModel = new MenuModel();
        $menus = $menusModel->getMenusByRole($roleID);

        // Ambil data lines dari model
        $linesModel = new LineModel();
        $lines = $linesModel->findAll();

        $jobTitleModel = new JobTitleModel();
        $m_jobTitles = $jobTitleModel->findAll();

        // Ambil data user job titles tanpa grouping
        $userJobTitles = $this->userJobTitleModel
            ->select('trUser_JobTitle.*, trUser_JobTitle.bitAchieved, mUser.txtFullName as userName, mJobTitle.txtJobTitle')
            ->join('mUser', 'mUser.intUserID = trUser_JobTitle.intUserID', 'left')
            ->join('mJobTitle', 'mJobTitle.intJobTitleID = trUser_JobTitle.intJobTitleID', 'left')
            ->findAll(); // Tidak ada pengelompokan, hasilkan semua data

        // Kirim data ke view
        $data = [
            'menus' => $menus,
            'lines' => $lines,
            'jobTitles' => $m_jobTitles,
            'userJobTitles' => $userJobTitles, // Pass raw data tanpa pengelompokan
            'pageTitle' => 'Auto Suggest System',
            'pageSubTitle' => 'Form ini digunakan untuk menyajikan list employee yang sesuai dengan job title dan line tertentu',
            'cardTitle' => 'Users',
            'icon' => 'users',
            'scripts' => 'assets/js/pages/transactions/user_jobtitle.js' // Kirim nama file script
        ];

        // Render view
        return view('transactions/user_jobtitle/auto_suggest/index', $data);
    }
}
