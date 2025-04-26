<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Helpers\Encrypt;
use CodeIgniter\I18n\Time;

class Auth extends BaseController
{
    public function index()
    {
        // Cek cookie untuk Remember Me
        $empID = get_cookie('empID');
        $password = get_cookie('password');

        // Jika cookie Remember Me ditemukan, coba login otomatis
        if ($empID && $password) {
            $userModel = new UserModel();
            $user = $userModel->verifyLogin($empID, $password);

            // Jika validasi berhasil, set session dan redirect ke Home
            if ($user) {
                session()->set([
                    'isLoggedIn' => true,
                    'userID' => $user['intUserID'],
                    'departmentID' => $user['intDepartmentID'],
                    'userName' => $user['txtUserName'],
                    'userFullName' => $user['txtFullName'],
                    'userEmail' => $user['txtEmail'],
                    'userNick' => $user['txtNick'], // Menambahkan txtNick ke sesi
                    'roleID' => $user['roleID'] // Menambahkan RoleID ke session
                ]);

                // Pengguna langsung diarahkan ke Home
                return redirect()->to('/');
            }
        }

        // Jika tidak ada Remember Me, tampilkan halaman login
        return view('login', ['validation' => \Config\Services::validation()]);
    }

    public function login()
    {
        // Ambil input dari form login
        $empID = $this->request->getPost('txtEmpID');
        $password = $this->request->getPost('txtPassword');
        $rememberMe = $this->request->getPost('remember_me'); // Checkbox Remember Me

        // Verifikasi login menggunakan model
        $userModel = new UserModel();
        $user = $userModel->verifyLogin($empID, $password);

        if ($user) {
            // Update dtmLastLogin untuk user yang berhasil login
            if ($userModel->updateLastLogin($user['intUserID'])) {
                // Set session untuk menyimpan data login
                session()->set([
                    'isLoggedIn' => true,
                    'userID' => $user['intUserID'],
                    'departmentID' => $user['intDepartmentID'],
                    'userName' => $user['txtUserName'],
                    'userFullName' => $user['txtFullName'],
                    'userEmail' => $user['txtEmail'],
                    'userNick' => $user['txtNick'], // Menambahkan txtNick ke sesi
                    'roleID' => $user['intRoleID'] // Menyimpan roleID ke sesi
                ]);

                // Jika Remember Me dicentang, simpan data login dalam cookie selama 30 hari
                if ($rememberMe) {
                    set_cookie('empID', $empID, 30 * 86400);  // 30 hari
                    set_cookie('password', $password, 30 * 86400);  // 30 hari
                } else {
                    // Jika Remember Me tidak dicentang, hapus cookie yang mungkin ada
                    delete_cookie('empID');
                    delete_cookie('password');
                }

                // Redirect ke Home setelah login berhasil
                return redirect()->to('/')->with('success', 'Login successful');
            } else {
                // Jika gagal mengupdate last login, kembalikan pesan error
                return redirect()->back()->withInput()->with('error', 'Failed to update last login time.');
            }
        } else {
            // Jika login gagal, kembalikan pesan error
            return redirect()->back()->withInput()->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        // Hapus semua session
        session()->destroy();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    public function forgotPassword()
    {
        return view('forgot_password', ['title' => 'Forgot Password']);
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');

        // Validasi email
        if (empty($email)) {
            return redirect()->back()->with('error', 'Email is required');
        }

        $userModel = new UserModel();

        // Verifikasi apakah email ada di database
        $user = $userModel->where('txtEmail', $email)->first();
        if (!$user) {
            return redirect()->to('/auth/forgot_password')->withInput()->with('error', 'Email not found');
        }

        // Buat token reset
        $token = bin2hex(random_bytes(50));

        // Debug
        log_message('debug', 'User ID: ' . $user['intUserID']);
        log_message('debug', 'Token to update: ' . $token);

        // Update token di database
        $updateData = [
            'reset_token' => $token,
            'token_created_at' => date('Y-m-d H:i:s') // Menyimpan waktu sekarang
        ];
        $userModel->update($user['intUserID'], $updateData); // Pastikan ini ada

        // Kirim email reset password
        $emailSent = $this->sendResetEmail($email, $token);
        if ($emailSent) {
            // Set flashdata untuk pesan sukses
            session()->setFlashdata('success', 'A reset link has been sent to your email.');
            return redirect()->to('/login');
        } else {
            return redirect()->to('/auth/forgot_password')->withInput()->with('error', 'Failed to send email');
        }
    }

    public function resetPassword($token)
    {
        // Cek apakah token valid
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        // Pastikan token ditemukan dan tidak kadaluarsa
        if (!$user || $this->isTokenExpired($user['token_created_at'])) {
            return redirect()->to('/login')->with('error', 'Invalid or expired token');
        }

        return view('reset_password', ['token' => $token, 'title' => 'Reset Password']);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $txtPassword = $this->request->getPost('txtPassword');

        // Validasi token dan update password di database
        $userModel = new UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        // Pastikan token valid
        if ($user && !$this->isTokenExpired($user['token_created_at'])) {
            // Hash password sebelum menyimpannya
            $hashedPassword = Encrypt::encryptPassword($txtPassword); // Pastikan Anda memiliki metode enkripsi
            $userModel->update($user['intUserID'], [
                'txtPassword' => $hashedPassword,
                'reset_token' => null, // Bersihkan token setelah digunakan
                'token_created_at' => null // Bersihkan waktu token
            ]);

            return redirect()->to('/login')->with('success', 'Password berhasil direset.');
        } else {
            return redirect()->back()->with('error', 'Token tidak valid atau telah kadaluarsa.');
        }
    }

    // Fungsi untuk memeriksa apakah token telah kadaluarsa
    private function isTokenExpired($tokenCreatedAt)
    {
        $createdAt = new \CodeIgniter\I18n\Time($tokenCreatedAt);
        $expiryTime = $createdAt->addHours(1); // Misalnya token berlaku selama 1 jam
        return Time::now() > $expiryTime; // Mengembalikan true jika sudah kadaluarsa
    }

    private function sendResetEmail($email, $token)
    {
        $emailService = \Config\Services::email();

        $emailService->setFrom('shp.digitalisasi@gmail.com', 'Developer E-Competency');
        $emailService->setTo($email);
        $emailService->setSubject('Reset Password');

        // Membuat isi email dalam format HTML
        $message = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
        <style>
            .button {
                background-color: #4CAF50; /* Hijau */
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                border-radius: 5px; /* Sudut membulat */
            }
        </style>
    </head>
    <body>
        <h2>Reset Password</h2>
        <p>Click the button below to reset your password:</p>
        <a href="' . base_url('auth/reset_password/' . $token) . '" class="button">Reset Password</a>
    </body>
    </html>
    ';

        $emailService->setMessage($message);
        $emailService->setMailType('html'); // Set email type menjadi HTML

        if ($emailService->send()) {
            return true;
        } else {
            log_message('error', 'Email not sent: ' . $emailService->printDebugger());
            return false;
        }
    }
}
