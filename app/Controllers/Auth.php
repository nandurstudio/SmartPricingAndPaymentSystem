<?php

namespace App\Controllers;

use App\Models\MTenantModel;
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

        // Load helper for consistent flash message handling and cookies
        helper(['flashmessage', 'cookie']);
    }

    public function index()
    {
        // Add debug logging
        log_message('debug', 'Checking for Remember Me cookies...');
        
        // Cek cookie untuk Remember Me
        $email = get_cookie('email');
        $password = get_cookie('password');
        
        log_message('debug', 'Cookie values - Email: ' . ($email ? 'found' : 'not found') . ', Password: ' . ($password ? 'found' : 'not found'));

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
                    'photo' => $user['txtPhoto'] // Menambahkan foto user
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
                        'txtGoogleAuthToken' => $googleUser->id,
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
                        'intTenantID' => $existingUser['intTenantID'] ?? null
                    ];

                    // Update data pengguna yang sudah ada
                    $userModel->update($existingUser['intUserID'], $updatedData);
                    // Ambil data pengguna yang sudah diperbarui
                    $user = $userModel->where('txtEmail', $googleUser->email)->first();
                }                // Set session
                session()->set([
                    'isLoggedIn' => true,
                    'userID' => $user['intUserID'],
                    'roleID' => $user['intRoleID'] ?? 5, // Default role 5 = customer
                    'userName' => $user['txtUserName'],
                    'userFullName' => $fullName,
                    'userEmail' => $user['txtEmail'],
                    'bitActive' => $user['bitActive'],
                    'lastLogin' => $user['dtmLastLogin'],
                    'joinDate' => $user['dtmJoinDate'],
                    'photo' => $profilePictureUrlHD,
                    'intTenantID' => $user['intTenantID'] ?? null
                ]);                // Cek apakah user perlu setup tenant
                if (!$user['intTenantID'] && !$existingUser) {
                    return redirect()->to('/tenants/create');
                }

                // Redirect ke dashboard jika sudah punya tenant
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/login')->with('error', 'Failed to get access token');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Invalid request');
        }
    } // End of googleCallback method
    public function login()
    {
        log_message('debug', 'Identity input: ' . $this->request->getPost('txtEmail'));
        log_message('debug', 'Password input: ' . $this->request->getPost('txtPassword'));
        log_message('debug', 'Remember Me input: ' . $this->request->getPost('remember_me'));

        $identity = $this->request->getPost('txtEmail'); // Bisa berisi email atau username
        $password = $this->request->getPost('txtPassword');
        $rememberMe = $this->request->getPost('remember_me');

        // Check if it's an AJAX request
        $isAjax = $this->request->isAJAX();

        // Validation
        $errors = [];

        if (empty($identity)) {
            $errors['fields']['email'] = 'Email or Username is required';
        }

        if (empty($password)) {
            $errors['fields']['password'] = 'Password is required';
        }

        if (!empty($errors)) {
            if ($isAjax) {
                $errors['error'] = 'Please check your input';
                return $this->response->setJSON($errors)->setStatusCode(422);
            } else {
                return redirect()->back()->with('error', 'Email/Username and Password are required')->withInput();
            }
        }

        $userModel = new \App\Models\MUserModel();
        $user = $userModel->verifyLoginByUsernameOrEmail($identity, $password);

        if ($user) {
            if ($userModel->updateLastLogin($user['intUserID'])) {
                // Get user with role name
                $userWithRole = $userModel->getUserWithRole($user['intUserID']);
                
                // Set session with role name
                session()->set([
                    'isLoggedIn' => true,
                    'userID' => $user['intUserID'],
                    'roleID' => $user['intRoleID'],
                    'roleName' => $userWithRole['txtRoleName'] ?? 'Unknown Role',
                    'userName' => $user['txtUserName'],
                    'userFullName' => $user['txtFullName'],
                    'userEmail' => $user['txtEmail'],
                    'bitActive' => $user['bitActive'],
                    'lastLogin' => $user['dtmLastLogin'],
                    'joinDate' => $user['dtmJoinDate'],
                    'photo' => $user['txtPhoto']
                ]);log_message('debug', 'Session set for user: ' . $user['txtUserName']);

                if ($rememberMe) {
                    log_message('debug', 'Setting remember me cookies...');
                      // Cookie settings
                    $cookieExpiry = 30 * 86400; // 30 days in seconds
                    $domain = getenv('cookie.domain') ?: '.smartpricingandpaymentsystem.localhost.com';
                    $path = getenv('cookie.path') ?: '/';

                    // Set email cookie with secure settings
                    $result1 = set_cookie('email', $user['txtEmail'], $cookieExpiry, $domain, $path, true, true);
                    
                    // Set password cookie (consider encrypting for better security)
                    $result2 = set_cookie('password', $password, $cookieExpiry);
                    
                    log_message('debug', 'Cookie set results - Email: ' . ($result1 ? 'success' : 'failed') . 
                              ', Password: ' . ($result2 ? 'success' : 'failed'));
                    
                    // Double check if cookies were set
                    $emailCookie = get_cookie('email');
                    log_message('debug', 'Cookie values after set - Email cookie exists: ' . 
                              ($emailCookie ? 'yes' : 'no'));
                } else {
                    log_message('debug', 'Removing remember me cookies...');
                    delete_cookie('email');
                    delete_cookie('password');
                }

                // Check if user has a tenant
                $tenantModel = new MTenantModel();
                $hasTenant = $tenantModel->where('intOwnerID', $user['intUserID'])->first();
                
                // Prepare redirect URL based on tenant ownership
                $redirectUrl = $hasTenant ? '/dashboard' : '/tenants/create';
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true, 
                        'redirect' => base_url($redirectUrl)
                    ]);
                } else {
                    return redirect()->to($redirectUrl);
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
    }    // Menampilkan halaman landing setelah login
    public function landingPage()
    {
        // Pastikan pengguna sudah login
        if (!session()->has('userID')) {
            // Jika belum login, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Ambil roleID dari session dan menu berdasarkan role
        $roleID = session()->get('roleID');
        $menuModel = new \App\Models\MenuModel();
        $menus = $menuModel->getMenusByRole($roleID);

        // Ambil statistik tenant dan user
        $tenantModel = new MTenantModel();
        $tenantCount = $tenantModel->countAll();
        $userModel = new MUserModel();
        $userCount = $userModel->countAll();

        // Tampilkan halaman landing setelah login
        return view('dashboard-2', [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'pageSubTitle' => 'Overview and statistics',
            'icon' => 'activity',
            'menus' => $menus,
            'tenantCount' => $tenantCount,
            'userCount' => $userCount
        ]);
    }

    public function logout()
    {
        // Get domain settings from .env
        $domain = getenv('cookie.domain') ?: '.smartpricingandpaymentsystem.localhost.com';
        $mainDomain = trim($domain, '.'); // Remove leading dot for alternate format
        $path = getenv('cookie.path') ?: '/';
        
        // Common paths to try
        $paths = ['/', '/login', '/auth', ''];
        
        // Delete cookies using multiple approaches for redundancy
        foreach ($paths as $path) {
            // Using CI's delete_cookie helper
            delete_cookie('email', $domain, $path);
            delete_cookie('password', $domain, $path);
            delete_cookie('email', $mainDomain, $path);
            delete_cookie('password', $mainDomain, $path);
            
            // Using PHP native setcookie with domain
            setcookie('email', '', time() - 3600, $path, $domain, true, true);
            setcookie('password', '', time() - 3600, $path, $domain, true, true);
            setcookie('email', '', time() - 3600, $path, $mainDomain, true, true);
            setcookie('password', '', time() - 3600, $path, $mainDomain, true, true);
            
            // Also try without domain
            setcookie('email', '', time() - 3600, $path, '', true, true);
            setcookie('password', '', time() - 3600, $path, '', true, true);
        }
        
        // Double check: unset cookies from $_COOKIE array
        if (isset($_COOKIE['email'])) unset($_COOKIE['email']);
        if (isset($_COOKIE['password'])) unset($_COOKIE['password']);
        
        // Set flash message before destroying session
        session()->setFlashdata('success', 'You have been logged out successfully.');
        
        // Destroy all session data
        session()->destroy();
        
        // Clear any remaining session cookies
        $session = session();
        $session->remove(['isLoggedIn', 'userID', 'roleID', 'userName', 'userFullName', 'userEmail']);
        
        // Log for debugging
        log_message('debug', 'Logout executed - Cookies and session cleared');
        
        // Redirect with cache control headers
        $response = redirect()->to('/login');
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $response->setHeader('Pragma', 'no-cache');
        return $response;
    }

    public function forgotPassword()
    {
        return view('forgot_password', ['title' => 'Forgot Password']);
    }
    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        log_message('debug', 'Send reset link requested for email: ' . $email);

        // Validasi email
        if (empty($email)) {
            log_message('debug', 'Email is empty');
            return redirect()->back()->with('error', 'Email address is required');
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            log_message('debug', 'Invalid email format: ' . $email);
            return redirect()->back()->withInput()->with('error', 'Please enter a valid email address');
        }

        $userModel = new MUserModel();

        // Verifikasi apakah email ada di database
        $user = $userModel->where('txtEmail', $email)->first();
        if (!$user) {
            log_message('debug', 'Email not found in database: ' . $email);

            // For security reasons, we still show a success message
            // This prevents email enumeration attacks
            $_SESSION['success'] = 'If your email exists in our system, a reset link has been sent.';
            session()->markAsFlashdata('success');

            return redirect()->to('/auth/forgot_password');
        }

        // Buat token reset
        $token = bin2hex(random_bytes(50));
        log_message('debug', 'Generated reset token: ' . substr($token, 0, 10) . '...');

        // Update token di database
        $updateData = [
            'txtResetToken' => $token, // was reset_token
            'dtmTokenCreatedAt' => date('Y-m-d H:i:s') // was token_created_at
        ];

        $updated = $userModel->update($user['intUserID'], $updateData);
        log_message('debug', 'Token updated in database: ' . ($updated ? 'Yes' : 'No'));

        // Kirim email reset password
        $emailSent = $this->sendResetEmail($email, $token);

        if ($emailSent) {
            log_message('debug', 'Reset email sent successfully to: ' . $email);
            // Use our helper function for consistent flash messages
            set_flash_message('success', 'A password reset link has been sent to your email. Please check your inbox.');
            log_message('debug', 'Flash data set using helper function');

            return redirect()->to('/auth/forgot_password');
        } else {
            log_message('error', 'Failed to send email to: ' . $email);
            return redirect()->to('/auth/forgot_password')
                ->withInput()
                ->with('error', 'Failed to send email. Please try again later or contact support.');
        }
    }
    public function resetPassword($token)
    {
        log_message('debug', 'Reset password page requested with token: ' . substr($token, 0, 10) . '...');

        // Validate token format first - basic security check
        if (empty($token) || strlen($token) < 32) {
            log_message('warning', 'Invalid token format attempted: ' . substr($token, 0, 10) . '...');
            set_flash_message('error', 'Invalid password reset link');
            return redirect()->to('/login');
        }

        // Cek apakah token valid
        $userModel = new MUserModel();
        $user = $userModel->where('txtResetToken', $token)->first();
        if (!$user) {
            log_message('warning', 'Reset token not found in database: ' . substr($token, 0, 10) . '...');
            set_flash_message('error', 'Invalid password reset link. Please request a new password reset.');
            return redirect()->to('/login');
        }

        // Cek apakah token kadaluarsa
        if ($this->isTokenExpired($user['dtmTokenCreatedAt'])) {
            log_message('warning', 'Expired reset token: ' . substr($token, 0, 10) . '...');
            set_flash_message('error', 'Your password reset link has expired. Please request a new link.');
            return redirect()->to('/auth/forgot_password');
        }

        log_message('debug', 'Valid token, showing reset password form');
        return view('reset_password', [
            'token' => $token,
            'title' => 'Reset Password',
            'email' => $user['txtEmail'], // Add email for display in the UI
            'username' => $user['txtUserName'] // Add username for display in the UI
        ]);
    }
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $txtPassword = $this->request->getPost('txtPassword');        // Log request parameters (excluding sensitive data)
        log_message('debug', 'Update password request received with token: ' . substr($token, 0, 8) . '...');

        // Validasi konfirmasi password
        $confirmPassword = $this->request->getPost('confirmPassword');
        if ($txtPassword !== $confirmPassword) {
            set_flash_message('error', 'Password confirmation does not match.');
            return redirect()->back()->withInput();
        }

        // Validasi token dan update password di database
        $userModel = new MUserModel();
        $user = $userModel->where('txtResetToken', $token)->first();        // Pastikan token valid        
        if (!$user) {
            log_message('warning', 'Invalid token used for password reset');
            set_flash_message('error', 'Invalid password reset token. Please request a new password reset link.');
            return redirect()->to(base_url('/login'));
        }

        if ($this->isTokenExpired($user['dtmTokenCreatedAt'])) {
            log_message('warning', 'Expired token used for password reset');
            set_flash_message('error', 'Your password reset link has expired. Please request a new one.');
            return redirect()->to(base_url('/auth/forgot_password'));
        }

        // Token valid, proceed with password update
        // Hash password sebelum menyimpannya
        $hashedPassword = password_hash($txtPassword, PASSWORD_DEFAULT);
        $updated = $userModel->update($user['intUserID'], [
            'txtPassword' => $hashedPassword,
            'txtResetToken' => null, // Bersihkan token setelah digunakan
            'dtmTokenCreatedAt' => null // Bersihkan waktu token
        ]);

        if (!$updated) {
            set_flash_message('error', 'Failed to update password. Please try again.');
            return redirect()->back()->withInput();
        }

        // Simpan pesan sukses ke session dan log aktivitas using our helper
        set_flash_message('success', 'Password berhasil direset. Silakan login dengan password baru.');

        log_message('info', 'Password reset successful for user: ' . $user['txtEmail']);
        log_message('debug', 'Flash message set using helper function');

        // Redirect ke login dengan base_url untuk memastikan path yang benar
        return redirect()->to(base_url('/login'));
    }    // Fungsi untuk memeriksa apakah token telah kadaluarsa
    private function isTokenExpired($tokenCreatedAt)
    {
        if (empty($tokenCreatedAt)) {
            log_message('debug', 'Token created date is empty, considering as expired');
            return true;
        }

        $createdAt = new \CodeIgniter\I18n\Time($tokenCreatedAt);
        $expiryTime = $createdAt->addHours(24); // Token berlaku selama 24 jam
        $isExpired = Time::now() > $expiryTime;

        log_message('debug', 'Token created at: ' . $tokenCreatedAt);
        log_message('debug', 'Token expires at: ' . $expiryTime->toDateTimeString());
        log_message('debug', 'Current time: ' . Time::now()->toDateTimeString());
        log_message('debug', 'Token expired: ' . ($isExpired ? 'Yes' : 'No'));

        return $isExpired; // Mengembalikan true jika sudah kadaluarsa
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

    /**
     * Display Terms of Service page
     */
    public function terms()
    {
        return view('terms', [
            'title' => 'Terms of Service'
        ]);
    }

    /**
     * Display Privacy Policy page
     */
    public function privacy()
    {
        return view('privacy', [
            'title' => 'Privacy Policy'
        ]);
    }
}
