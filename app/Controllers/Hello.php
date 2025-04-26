<?php

namespace App\Controllers;

class Hello extends BaseController
{
    public function index(): string
    {
        // Cek koneksi ke database
        $db = \Config\Database::connect(); // Koneksi ke database menggunakan konfigurasi default
        $query = $db->query('SELECT DATABASE() AS db_name'); // Ambil nama database dengan alias
        $row = $query->getRow(); // Ambil hasil query

        // Menampilkan pesan berhasil terkoneksi dengan nama database dan link logout
        return "
<h1>Database connected: " . $row->db_name . "</h1>
<a href='/hello/logout'>Logout</a>
"; // Gunakan nama kolom 'db_name' sebagai gantinya
    }

    public function logout()
    {
        // Hapus session
        session()->destroy();

        // Redirect ke halaman login
        return redirect()->to('/login');
    }
}
