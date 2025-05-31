<?php

namespace Config;

use Config\Services;

$routes = Services::routes();

// Default route
// Menambahkan route untuk halaman default (landing) setelah login
$routes->get('/', 'Auth::landingPage', ['filter' => 'auth']);
$routes->get('landing', 'Auth::landingPage', ['filter' => 'auth']);  // Pastikan ada route ini

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

$routes->group('user', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'UserController::index');                   // User management dashboard
    $routes->get('edit/(:num)', 'UserController::edit/$1');      // Edit user form
    $routes->post('update/(:num)', 'UserController::update/$1'); // Process user update
    $routes->get('view/(:num)', 'UserController::view/$1');      // View user details
    $routes->post('toggle-status/(:num)', 'UserController::toggleStatus/$1'); // Toggle user status
});

$routes->group('role', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'RoleController::index');
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

// Debug routes - only for administrator use
$routes->group('debug', ['filter' => 'auth'], function ($routes) {
    $routes->get('test-google-registration', 'DebugController::testGoogleRegistration');
    $routes->get('check-guid-values', 'DebugController::checkGuidValues');
});