<?php

namespace App\Controllers;

use App\Models\MUserModel;
use CodeIgniter\I18n\Time;
use Google_Client;
use Google\Service\Oauth2;

class Auth extends BaseController
{
    private $googleClient;

    public function __construct()
    {
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId(getenv('google.client_id'));
        $this->googleClient->setClientSecret(getenv('google.client_secret'));
        $this->googleClient->setRedirectUri(getenv('google.redirect_uri'));

        // Menambahkan scope 'email' dan 'profile'
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        // Cek cookie untuk Remember Me
        $email = get_cookie('email');
        $password = get_cookie('password');

        // Jika cookie Remember Me ditemukan, coba login otomatis
        if ($email && $password) {
            $userModel = new MUserModel();
            $user = $userModel->verifyLoginByEmail($email, $password);

            // Jika validasi berhasil, set session dan redirect ke Home
            if ($user) {
                session()->set([
                    'isLoggedIn' => true,
                    'userID' => $user['intUserID'],
                    'roleID' => $user['intRoleID'],
                    'userName' => $user['txtUserName'],
                    'userFullName' => $user['txtFullName'],
                    'userEmail' => $user['txtEmail'],
                    'bitActive' => $user['bitActive'], // bitActive untuk status aktif user
                    'lastLogin' => $user['dtmLastLogin'], // Menambahkan waktu login terakhir
                    'joinDate' => $user['dtmJoinDate'], // Menambahkan tanggal bergabung
                    'photo' => $user['txtPhoto'], // Menambahkan foto user
                ]);

                // Pengguna langsung diarahkan ke Home
                return redirect()->to('/');
            }
        }

        // Jika tidak ada Remember Me, tampilkan halaman login
        return view('login', ['validation' => \Config\Services::validation()]);
    }

    // Method untuk login dengan Google
    public function googleLogin()
    {
        $authUrl = $this->googleClient->createAuthUrl();
        return redirect()->to($authUrl); // Redirect ke halaman login Google
    }    // Callback dari Google setelah login
    public function googleCallback()
    {
        $code = $this->request->getGet('code');

        if ($code) {
            // Mendapatkan token akses dengan kode otorisasi
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($code);

            if (isset($token['access_token'])) {
                $this->googleClient->setAccessToken($token['access_token']);

                // Mengambil data profil pengguna dari Google
                $oauth = new Oauth2($this->googleClient);
                $googleUser = $oauth->userinfo->get();  // Ambil data profil pengguna
                log_message('debug', 'Google User Data: ' . print_r($googleUser, true));

                // Model pengguna
                $userModel = new \App\Models\MUserModel();

                // Membentuk fullName dari Google jika tidak kosong
                $fullName = trim(($googleUser->givenName ?? '') . ' ' . ($googleUser->familyName ?? ''));
                if (empty($fullName)) {
                    $fullName = $googleUser->email;
                }

                // Mengambil txtUserName dari alamat email sebelum @
                $emailParts = explode('@', $googleUser->email);
                $userName = $emailParts[0]; // Bagian sebelum @

                // Cek apakah pengguna sudah ada berdasarkan email
                $existingUser = $userModel->where('txtEmail', $googleUser->email)->first();

                // Mengambil foto dalam resolusi HD
                $profilePictureUrl = $googleUser->picture;
                $profilePictureUrlHD = str_replace("=s96-c", "", $profilePictureUrl); // Hapus parameter ukuran (misalnya s96)

                if (!$existingUser) {
                    // Jika pengguna belum terdaftar, buat data pengguna baru
                    $newUserData = [
                        'txtEmail' => $googleUser->email,
                        'txtUserName' => $userName, // Menggunakan bagian email sebelum @ sebagai username
                        'txtFullName' => $fullName,
                        'txtPhoto' => $profilePictureUrlHD, // Simpan foto dalam resolusi HD
                        'bitActive' => 1,
                        'intRoleID' => 5, // Default role for Customer
                        'dtmJoinDate' => date('Y-m-d H:i:s'),
                        'dtmLastLogin' => date('Y-m-d H:i:s'),
                        'txtGUID' => uniqid('google_', true), // Generate a unique ID dengan prefix google_ untuk tracking
                        'dtmCreatedDate' => date('Y-m-d H:i:s'),
                        'txtCreatedBy' => 'google_auth',
                        'google_auth_token' => $googleUser->id,
                    ];

                    // Insert data pengguna baru
                    $userModel->insert($newUserData);
                    // Ambil data pengguna yang baru ditambahkan
                    $user = $userModel->where('txtEmail', $googleUser->email)->first();
                } else {
                    // Jika pengguna sudah ada, update data pengguna
                    $updatedData = [
                        'txtUserName' => $userName, // Update username
                        'txtFullName' => $fullName, // Update fullname
                        'txtPhoto' => $profilePictureUrlHD, // Update photo jika perlu dengan foto HD
                        'dtmLastLogin' => date('Y-m-d H:i:s'), // Update last login
                    ];

                    // Update data pengguna yang sudah ada
                    $userModel->update($existingUser['intUserID'], $updatedData);
                    // Ambil data pengguna yang sudah diperbarui
                    $user = $userModel->where('txtEmail', $googleUser->email)->first();
                }

                // Set session login setelah berhasil
                session()->set([
                    'isLoggedIn' => true,
                    'userID' => $user['intUserID'],
                    'roleID' => $user['intRoleID'] ?? 2, // Default role misalnya 2 (user biasa)
                    'userName' => $user['txtUserName'],
                    'userFullName' => $fullName, // Pastikan fullName disimpan di session
                    'userEmail' => $user['txtEmail'],
                    'bitActive' => $user['bitActive'],
                    'lastLogin' => $user['dtmLastLogin'],
                    'joinDate' => $user['dtmJoinDate'],
                    'photo' => $profilePictureUrlHD, // Foto dengan resolusi lebih tinggi
                ]);

                // Redirect ke halaman landing setelah berhasil login
                return redirect()->to('/landing');
            } else {
                return redirect()->to('/auth')->with('error', 'Failed to get access token');
            }        } else {
            return redirect()->to('/auth')->with('error', 'Invalid request');
        }
    } // End of googleCallback method
    
    public function login()
    {
        log_message('debug', 'Email input: ' . $this->request->getPost('txtEmail'));
        log_message('debug', 'Password input: ' . $this->request->getPost('txtPassword'));
        log_message('debug', 'Remember Me input: ' . $this->request->getPost('remember_me'));

        $email = $this->request->getPost('txtEmail');
        $password = $this->request->getPost('txtPassword');
        $rememberMe = $this->request->getPost('remember_me');
        
        // Check if it's an AJAX request
        $isAjax = $this->request->isAJAX();
        
        // Validation
        $errors = [];
        
        if (empty($email)) {
            $errors['fields']['email'] = 'Email is required';
        }
        
        if (empty($password)) {
            $errors['fields']['password'] = 'Password is required';
        }
        
        if (!empty($errors)) {
            if ($isAjax) {
                $errors['error'] = 'Please check your input';
                return $this->response->setJSON($errors)->setStatusCode(422);
            } else {
                return redirect()->back()->with('error', 'Email and Password are required')->withInput();
            }
        }

        $userModel = new \App\Models\MUserModel();
        $user = $userModel->verifyLoginByEmail($email, $password);

        if ($user) {
            if ($userModel->updateLastLogin($user['intUserID'])) {
                // Set session
                session()->set([
                    'isLoggedIn' => true,
                    'userID' => $user['intUserID'],
                    'roleID' => $user['intRoleID'],
                    'userName' => $user['txtUserName'],
                    'userFullName' => $user['txtFullName'],
                    'userEmail' => $user['txtEmail'],
                    'bitActive' => $user['bitActive'],
                    'lastLogin' => $user['dtmLastLogin'],
                    'joinDate' => $user['dtmJoinDate'],
                    'photo' => $user['txtPhoto'],
                ]);

                log_message('debug', 'Session set for user: ' . $user['txtUserName']);

                if ($rememberMe) {
                    set_cookie('email', $email, 30 * 86400);
                    set_cookie('password', $password, 30 * 86400);
                } else {
                    delete_cookie('email');
                    delete_cookie('password');
                }
                
                if ($isAjax) {
                    return $this->response->setJSON(['success' => true, 'redirect' => base_url('/landing')]);
                } else {
                    return redirect()->to('/landing'); // Redirect ke halaman landing setelah login
                }
            } else {
                $error = 'Failed to update last login time.';
                if ($isAjax) {
                    return $this->response->setJSON(['error' => $error])->setStatusCode(500);
                } else {
                    return redirect()->back()->withInput()->with('error', $error);
                }
            }
        } else {
            $error = 'Invalid email or password';
            if ($isAjax) {
                return $this->response->setJSON(['error' => $error])->setStatusCode(401);
            } else {
                return redirect()->back()->withInput()->with('error', $error);
            }
        }
    }

    // Menampilkan halaman landing setelah login
    public function landingPage()
    {
        // Pastikan pengguna sudah login
        if (!session()->has('userID')) {
            // Jika belum login, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Tampilkan halaman landing setelah login
        return view('dashboard-2'); // Pastikan Anda memiliki view 'landing.php'
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

        $userModel = new MUserModel();

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
        $userModel = new MUserModel();
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
        $userModel = new MUserModel();
        $user = $userModel->where('reset_token', $token)->first();

        // Pastikan token valid
        if ($user && !$this->isTokenExpired($user['token_created_at'])) {
            // Hash password sebelum menyimpannya
            $hashedPassword = password_hash($txtPassword, PASSWORD_DEFAULT);
            $userModel->update($user['intUserID'], [
                'txtPassword' => $hashedPassword,
                'reset_token' => null, // Bersihkan token setelah digunakan
                'token_created_at' => null // Bersihkan waktu token
            ]);

            // Simpan pesan sukses ke session dan log aktivitas
            session()->setFlashdata('success', 'Password berhasil direset. Silakan login dengan password baru.');
            log_message('info', 'Password reset successful for user: ' . $user['txtEmail']);
            return redirect()->to('/login');
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

        $emailService->setFrom('founder@nandurstudio.com', 'Developer Kelompok 5');
        $emailService->setTo($email);
        $emailService->setSubject('Reset Password');

        // Email HTML template yang lebih modern dan responsif
        $resetUrl = base_url('auth/reset_password/' . $token);
        $message = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', Arial, sans-serif; color: #222; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 32px 24px; }
        .logo { text-align: center; margin-bottom: 24px; }
        .logo img { width: 64px; }
        h2 { color: #2d3748; text-align: center; margin-bottom: 12px; }
        p { color: #4a5568; text-align: center; }
        .button {
            display: block;
            width: 100%;
            background: #2563eb;
            color: #fff !important;
            text-decoration: none;
            padding: 14px 0;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 28px 0 12px 0;
            text-align: center;
            transition: background 0.2s;
        }
        .button:hover { background: #1d4ed8; }
        .footer { text-align: center; color: #a0aec0; font-size: 0.95em; margin-top: 32px; }
        @media (max-width: 600px) {
            .container { padding: 18px 6px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" alt="Lock Icon">
        </div>
        <h2>Reset Your Password</h2>
        <p>We received a request to reset your password.<br>
        Click the button below to set a new password for your account.</p>
        <a href="$resetUrl" class="button">Reset Password</a>
        <p style="font-size:0.97em; color:#718096; margin-top:18px;">If you did not request a password reset, please ignore this email.<br>This link will expire in 1 hour.</p>
        <div class="footer">&copy; 2025 Smart Pricing and Payment System</div>
    </div>
</body>
</html>
HTML;

        $emailService->setMessage($message);
        $emailService->setMailType('html');

        if ($emailService->send()) {
            return true;
        } else {
            log_message('error', 'Email not sent: ' . $emailService->printDebugger());
            return false;
        }
    }
}
