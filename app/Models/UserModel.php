<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Helpers\Encrypt; // Tambahkan helper untuk enkripsi

class UserModel extends Model
{
    protected $table = 'mUser'; // Pastikan ini sesuai dengan nama tabel di database
    protected $primaryKey = 'intUserID'; // Primary key
    protected $allowedFields = [
        'intRoleID',
        'intJobTitleID',
        'intSupervisorID',
        'intLineID',
        'intDepartmentID',
        'txtUserName',
        'txtFullName',
        'txtNick',
        'txtEmpID',
        'txtEmail',
        'txtPassword',
        'bitActive',
        'txtInsertedBy',
        'txtUpdatedBy',
        'txtGUID',
        'reset_token',
        'token_created_at',
        'dtmJoinDate',
        'txtPhoto' // Tambahkan ini
    ];

    // Optional: Untuk timestamps otomatis
    protected $useTimestamps = true;
    protected $createdField = 'dtmInsertedDate';
    protected $updatedField = 'dtmUpdatedDate';

    // Metode untuk memverifikasi login
    public function verifyLogin($empID, $password)
    {
        $user = $this->where('txtEmpID', $empID)->first();

        if ($user) {
            // Dekripsi password dari database
            $decryptedPassword = Encrypt::decryptPassword($user['txtPassword']);

            // Cocokkan password yang diinput dengan password yang didekripsi
            if ($password === $decryptedPassword) {
                return [
                    'intUserID' => $user['intUserID'],
                    'intJobTitleID' => $user['intJobTitleID'],
                    'intSupervisorID' => $user['intSupervisorID'],
                    'intLineID' => $user['intLineID'],
                    'intDepartmentID' => $user['intDepartmentID'],
                    'txtUserName' => $user['txtUserName'],
                    'txtFullName' => $user['txtFullName'],
                    'txtEmail' => $user['txtEmail'],
                    'txtNick' => $user['txtNick'],
                    'intRoleID' => $user['intRoleID'], // Ambil roleID juga
                ]; // Login berhasil
            }
        }
        return false; // Login gagal
    }

    public function getUsers($start, $length, $searchValue, $orderBy, $orderDirection)
    {
        // Pastikan builder dideklarasikan dengan benar
        $builder = $this->db->table('muser');
        $builder->select('muser.*, mRole.txtRoleName'); // Ambil txtRoleName dari tabel mRole
        $builder->join('mRole', 'muser.intRoleID = mRole.intRoleID', 'left'); // Join ke tabel mRole

        // Tambahkan pencarian jika diperlukan
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('muser.txtFullName', $searchValue)
                ->orLike('muser.txtUserName', $searchValue)
                ->orLike('mRole.txtRoleName', $searchValue) // Tambahkan pencarian berdasarkan RoleName
                ->orLike('muser.txtNick', $searchValue)
                ->orLike('muser.txtEmpID', $searchValue)
                ->orLike('muser.txtEmail', $searchValue)
                ->groupEnd();
        }

        // Tambahkan sorting
        $builder->orderBy($orderBy, $orderDirection);

        // Batasi jumlah data yang dikembalikan
        $builder->limit($length, $start);

        // Eksekusi query dan kembalikan hasilnya
        return $builder->get()->getResultArray();
    }

    public function countAllUsers($searchValue = null)
    {
        $builder = $this->db->table('muser');
        $builder->select('muser.*, mRole.txtRoleName');
        $builder->join('mRole', 'muser.intRoleID = mRole.intRoleID', 'left');

        if ($searchValue) {
            $builder->groupStart()
                ->like('muser.txtFullName', $searchValue)
                ->orLike('muser.txtUserName', $searchValue)
                ->orLike('mRole.txtRoleName', $searchValue) // Tambahkan pencarian berdasarkan RoleName
                ->orLike('muser.txtNick', $searchValue)
                ->orLike('muser.txtEmpID', $searchValue)
                ->orLike('muser.txtEmail', $searchValue)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    public function updateLastLogin($userID)
    {
        $data = [
            'dtmLastLogin' => date('Y-m-d H:i:s'), // Waktu sekarang
        ];

        // Memastikan bahwa userID yang diberikan valid dan ada di database
        if ($this->find($userID)) {
            $builder = $this->db->table('mUser'); // Ganti 'users' dengan nama tabel Anda
            $builder->where('intUserID', $userID);

            // Jalankan update dan cek apakah berhasil
            if ($builder->update($data)) {
                return true; // Update berhasil
            } else {
                log_message('error', 'Failed to update last login for userID: ' . $userID);
                return false; // Update gagal
            }
        } else {
            throw new \Exception("User ID tidak valid: $userID");
        }
    }
}
