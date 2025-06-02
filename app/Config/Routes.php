<?php

namespace Config;

use Config\Services;

$routes = Services::routes();

// Default route
// Menambahkan route untuk halaman default (landing) setelah login
$routes->get('/', 'Auth::landingPage', ['filter' => 'auth']);
$routes->get('landing', 'Auth::landingPage', ['filter' => 'auth']);  // Pastikan ada route ini
$routes->get('dashboard', 'Auth::landingPage', ['filter' => 'auth']); // Route for /dashboard to use the same landing page

$routes->get('/auth/googleLogin', 'Auth::googleLogin');
$routes->get('/auth/google/callback', 'Auth::googleCallback');

// Route untuk login
$routes->get('login', 'Auth::index');  // Menampilkan halaman login
$routes->post('login', 'Auth::login');  // Mengirim data login
$routes->post('auth/login', 'Auth::login');  // Alternatif URL untuk login (untuk compatibility)
$routes->get('logout', 'Auth::logout');  // Mengeluarkan pengguna

// Route untuk register
$routes->get('/register', 'Register::index');  // Menampilkan halaman registrasi
$routes->post('/register/createUser', 'Register::createUser');  // Mengirim data registrasi
$routes->post('/register/checkUsername', 'Register::checkUsername');
$routes->post('/register/checkEmail', 'Register::checkEmail');


// Route untuk forgot password
$routes->get('auth/forgot_password', 'Auth::forgotPassword');  // Menampilkan halaman lupa password
$routes->post('auth/sendResetLink', 'Auth::sendResetLink');  // Mengirim link reset password
$routes->get('auth/reset_password/(:any)', 'Auth::resetPassword/$1');  // Menampilkan halaman reset password
$routes->post('auth/updatePassword', 'Auth::updatePassword');  // Memperbarui password

$routes->group('users', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'UserController::index');                   // User management dashboard
    $routes->get('edit/(:num)', 'UserController::edit/$1');      // Edit user form
    $routes->post('update/(:num)', 'UserController::update/$1'); // Process user update
    $routes->get('view/(:num)', 'UserController::view/$1');      // View user details
    $routes->post('toggle-status/(:num)', 'UserController::toggleStatus/$1'); // Toggle user status
});

$routes->group('roles', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RoleController::index');
    $routes->post('data', 'RoleController::data'); // DataTables endpoint
    $routes->get('create', 'RoleController::create');
    $routes->post('store', 'RoleController::store');
    $routes->get('edit/(:num)', 'RoleController::edit/$1');
    $routes->get('view/(:num)', 'RoleController::view/$1'); // Pastikan ini ada
    $routes->post('update/(:num)', 'RoleController::update/$1');
});

$routes->group('role_menu_access', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RoleMenuAccessController::index');
    $routes->get('create', 'RoleMenuAccessController::create');
    $routes->post('store', 'RoleMenuAccessController::store');
    $routes->get('view/(:num)', 'RoleMenuAccessController::view/$1');
    $routes->get('edit/(:num)', 'RoleMenuAccessController::edit/$1');
    $routes->post('update/(:num)', 'RoleMenuAccessController::update/$1');
});

$routes->group('menu', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'MenuController::index');
    $routes->get('create', 'MenuController::create');
    $routes->post('store', 'MenuController::store');
    $routes->get('view/(:num)', 'MenuController::view/$1');
    $routes->get('edit/(:num)', 'MenuController::edit/$1');
    $routes->post('update/(:num)', 'MenuController::update/$1');
});

$routes->group('product', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ProductController::index');
    $routes->get('add', 'ProductController::add');
    $routes->post('store', 'ProductController::store');
    $routes->get('view/(:num)', 'ProductController::view/$1');
    $routes->get('edit/(:num)', 'ProductController::edit/$1');
    $routes->post('update/(:num)', 'ProductController::update/$1');
    $routes->get('delete/(:num)', 'ProductController::delete/$1');
});

// Tenant routes
$routes->group('tenant', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'TenantController::index');
    $routes->get('create', 'TenantController::create');
    $routes->post('store', 'TenantController::store');
    $routes->get('view/(:num)', 'TenantController::view/$1');
    $routes->get('edit/(:num)', 'TenantController::edit/$1');
    $routes->post('update/(:num)', 'TenantController::update/$1');
});

// Service routes
$routes->group('service', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ServiceController::index');
    $routes->get('create', 'ServiceController::create');
    $routes->post('store', 'ServiceController::store');
    $routes->get('view/(:num)', 'ServiceController::view/$1');
    $routes->get('edit/(:num)', 'ServiceController::edit/$1');
    $routes->post('update/(:num)', 'ServiceController::update/$1');
});

// Booking routes
$routes->group('booking', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'BookingController::index');
    $routes->get('create', 'BookingController::create');
    $routes->post('store', 'BookingController::store');
    $routes->get('view/(:num)', 'BookingController::view/$1');
    $routes->get('cancel/(:num)', 'BookingController::cancel/$1');
    $routes->get('payment/(:num)', 'BookingController::payment/$1');
});

// Schedule routes
$routes->group('schedule', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ScheduleController::index');
    $routes->get('create', 'ScheduleController::create');
    $routes->post('store', 'ScheduleController::store');
    $routes->get('edit/(:num)', 'ScheduleController::edit/$1');
    $routes->post('update/(:num)', 'ScheduleController::update/$1');
    $routes->get('special', 'ScheduleController::special');
    $routes->post('storeSpecial', 'ScheduleController::storeSpecial');
});

// Onboarding routes
$routes->group('onboarding', ['filter' => 'auth'], function ($routes) {
    $routes->get('setup-tenant', 'OnboardingController::setupTenant');
    $routes->post('create-tenant', 'OnboardingController::createTenant');
    $routes->get('setup-branding/(:num)', 'OnboardingController::setupBranding/$1');
    $routes->post('update-branding/(:num)', 'OnboardingController::updateBranding/$1');
});

// Debug routes - only for administrator use
$routes->group('debug', ['filter' => 'auth'], function ($routes) {
    $routes->get('test-google-registration', 'DebugController::testGoogleRegistration');
    $routes->get('check-guid-values', 'DebugController::checkGuidValues');
});

// Onboarding routes
$routes->group('onboarding', ['filter' => 'auth'], function ($routes) {
    $routes->get('setup-tenant', 'OnboardingController::setupTenant');
    $routes->post('create-tenant', 'OnboardingController::createTenant');
    $routes->get('setup-branding/(:num)', 'OnboardingController::setupBranding/$1');
    $routes->post('update-branding/(:num)', 'OnboardingController::updateBranding/$1');
});

// API Routes for booking time slots
$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('get-available-slots/(:num)', 'Api\TimeSlotController::getAvailableSlots/$1');
});

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// Role Management Routes
$routes->group('roles', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'RoleController::index');
    $routes->post('data', 'RoleController::data'); // DataTables endpoint
    $routes->get('create', 'RoleController::create');
    $routes->post('store', 'RoleController::store');
    $routes->get('edit/(:num)', 'RoleController::edit/$1');
    $routes->post('update/(:num)', 'RoleController::update/$1');
    $routes->get('view/(:num)', 'RoleController::view/$1');
});