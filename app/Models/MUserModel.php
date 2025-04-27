<?php

namespace App\Models;

use CodeIgniter\Model;

class MUserModel extends Model
{
    protected $table = 'm_user'; // Nama tabel yang sesuai dengan database
    protected $primaryKey = 'intUserID'; // Primary key
    protected $allowedFields = [
        'intRoleID',
        'txtUserName',
        'txtFullName',
        'txtEmail',
        'txtPassword',
        'bitActive',
        'dtmLastLogin',
        'txtCreatedBy', // Sesuaikan dengan created
        'dtmCreatedDate', // Sesuaikan dengan created
        'txtUpdatedBy',
        'dtmUpdatedDate',
        'txtGUID',
        'reset_token',
        'token_created_at',
        'txtPhoto',
        'dtmJoinDate',
        'bitOnlineStatus',  // Field tambahan untuk online status
        'google_auth_token' // Field tambahan untuk Google Auth SSO
    ];

    // Optional: Untuk timestamps otomatis
    protected $useTimestamps = true;
    protected $createdField = 'dtmCreatedDate'; // Menyesuaikan dengan field created
    protected $updatedField = 'dtmUpdatedDate';

    // Fungsi untuk verifikasi login
    public function verifyLoginByEmail($email, $password)
    {
        $user = $this->where('txtEmail', $email)->first();
        log_message('debug', 'User Data: ' . print_r($user, true)); // Log untuk memastikan data yang diterima

        if ($user && password_verify($password, $user['txtPassword'])) {
            log_message('debug', 'Login successful for user: {0}', [$user['txtUserName']]);
            return [
                'intUserID' => $user['intUserID'],
                'txtUserName' => $user['txtUserName'],
                'txtFullName' => $user['txtFullName'],
                'txtEmail' => $user['txtEmail'],
                'intRoleID' => $user['intRoleID'],
                'bitActive' => $user['bitActive'],
                'bitOnlineStatus' => $user['bitOnlineStatus'] ?? 0, // default 0 kalau gak ada
                'dtmLastLogin' => $user['dtmLastLogin'], // Tambahkan kolom dtmLastLogin di sini
                'dtmJoinDate' => $user['dtmJoinDate'],
                'txtPhoto' => $user['txtPhoto']
            ];
        }

        log_message('debug', 'Login failed. Email input: ' . var_export($email, true));
        return false;
    }

    // Fungsi untuk hash password sebelum disimpan (saat registrasi atau update)
    public function hashPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    public function insertNewUserFromGoogle($googleUser)
    {
        $newUser = [
            'txtEmail'          => $googleUser->email,
            'txtUserName'       => !empty($googleUser->givenName) ? $googleUser->givenName : (!empty($googleUser->name) ? $googleUser->name : 'user_' . time()),
            'txtFullName'       => $googleUser->name ?? '',
            'txtPhoto'          => $googleUser->picture ?? null,
            'bitActive'         => 1,
            'dtmJoinDate'       => date('Y-m-d H:i:s'),
            'dtmLastLogin'      => date('Y-m-d H:i:s'),
            'google_auth_token' => $googleUser->id ?? null,
            'txtCreatedBy'      => 'google_oauth',
            'dtmCreatedDate'    => date('Y-m-d H:i:s'),
            'txtGUID'           => uniqid(),
            'intRoleID'         => 2, // Optional: role default user
        ];

        $existingUser = $this->where('txtEmail', $googleUser->email)->first();

        if (!$existingUser) {
            if ($this->insert($newUser)) {
                return $this->where('txtEmail', $googleUser->email)->first();
            } else {
                throw new \RuntimeException('Gagal menambahkan pengguna baru dari Google.');
            }
        } else {
            return $existingUser;
        }
    }

    // Fungsi untuk mendapatkan daftar pengguna
    public function getUsers($start, $length, $searchValue, $orderBy, $orderDirection)
    {
        // Query untuk mengambil data pengguna
        $builder = $this->db->table('m_user');
        $builder->select('m_user.*, m_role.txtRoleName'); // Mengambil txtRoleName dari tabel m_role
        $builder->join('m_role', 'm_user.intRoleID = m_role.intRoleID', 'left'); // Join ke tabel m_role

        // Pencarian berdasarkan beberapa field
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('m_user.txtFullName', $searchValue)
                ->orLike('m_user.txtUserName', $searchValue)
                ->orLike('m_role.txtRoleName', $searchValue)
                ->orLike('m_user.txtEmail', $searchValue)
                ->groupEnd();
        }

        // Sorting
        $builder->orderBy($orderBy, $orderDirection);

        // Batasi jumlah data yang dikembalikan
        $builder->limit($length, $start);

        // Eksekusi dan kembalikan hasil query
        return $builder->get()->getResultArray();
    }

    public function countAllUsers($searchValue = null)
    {
        $builder = $this->db->table('m_user');
        $builder->select('m_user.*, m_role.txtRoleName');
        $builder->join('m_role', 'm_user.intRoleID = m_role.intRoleID', 'left');

        // Pencarian berdasarkan nilai yang diberikan
        if ($searchValue) {
            $builder->groupStart()
                ->like('m_user.txtFullName', $searchValue)
                ->orLike('m_user.txtUserName', $searchValue)
                ->orLike('m_role.txtRoleName', $searchValue)
                ->orLike('m_user.txtEmail', $searchValue)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    public function updateLastLogin($userID)
    {
        $data = [
            'dtmLastLogin' => date('Y-m-d H:i:s'), // Waktu login terakhir
        ];

        // Pastikan userID valid
        if ($this->find($userID)) {
            $builder = $this->db->table('m_user');
            $builder->where('intUserID', $userID);

            // Update data
            if ($builder->update($data)) {
                return true;
            } else {
                log_message('error', 'Failed to update last login for userID: ' . $userID);
                return false;
            }
        } else {
            throw new \Exception("User ID tidak valid: $userID");
        }
    }
}
