<?php

namespace Config;

use Config\Services;

$routes = Services::routes();

// Default route
$routes->get('/', 'Auth::landingPage', ['filter' => 'auth']);
$routes->get('landing', 'Auth::landingPage', ['filter' => 'auth']);
$routes->get('dashboard', 'Auth::landingPage', ['filter' => 'auth']);

// Authentication routes
$routes->get('/auth/googleLogin', 'Auth::googleLogin');
$routes->get('/auth/google/callback', 'Auth::googleCallback');
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Registration routes
$routes->get('/register', 'Register::index');
$routes->post('/register/createUser', 'Register::createUser');
$routes->post('/register/checkUsername', 'Register::checkUsername');
$routes->post('/register/checkEmail', 'Register::checkEmail');

// Password management routes
$routes->get('auth/forgot_password', 'Auth::forgotPassword');
$routes->post('auth/sendResetLink', 'Auth::sendResetLink');
$routes->get('auth/reset_password/(:any)', 'Auth::resetPassword/$1');
$routes->post('auth/updatePassword', 'Auth::updatePassword');

// Protected routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // User management - accessible via both /master/users and /users
    $routes->group('users', function ($routes) {
        $routes->get('/', 'UserController::index');
        $routes->get('edit/(:num)', 'UserController::edit/$1');
        $routes->post('update/(:num)', 'UserController::update/$1');
        $routes->get('view/(:num)', 'UserController::view/$1');
        $routes->post('toggle-status/(:num)', 'UserController::toggleStatus/$1');
    });
    
    // Master routes that map to the same controllers
    $routes->group('master', function($routes) {
        // Map /master/users/* to the same UserController
        $routes->get('users', 'UserController::index');
        $routes->addRedirect('users/(:any)', '../users/$1');
        
        // Roles routes in master section
        $routes->get('roles', 'RoleController::index');
        $routes->addRedirect('roles/(:any)', '../roles/$1');
    });

    // Role management
    $routes->group('roles', function ($routes) {
        $routes->get('/', 'RoleController::index');
        $routes->post('data', 'RoleController::data');
        $routes->get('create', 'RoleController::create');
        $routes->post('store', 'RoleController::store');
        $routes->get('edit/(:num)', 'RoleController::edit/$1');
        $routes->get('view/(:num)', 'RoleController::view/$1');
        $routes->post('update/(:num)', 'RoleController::update/$1');
    });

    // Menu access management
    $routes->group('role-menu-access', function ($routes) {
        $routes->get('/', 'RoleMenuAccessController::index');
        $routes->get('create', 'RoleMenuAccessController::create');
        $routes->post('store', 'RoleMenuAccessController::store');
        $routes->get('view/(:num)', 'RoleMenuAccessController::view/$1');
        $routes->get('edit/(:num)', 'RoleMenuAccessController::edit/$1');
        $routes->post('update/(:num)', 'RoleMenuAccessController::update/$1');
    });

    // Menu management
    $routes->group('menu', function ($routes) {
        $routes->get('/', 'MenuController::index');
        $routes->get('create', 'MenuController::create');
        $routes->post('store', 'MenuController::store');
        $routes->get('view/(:num)', 'MenuController::view/$1');
        $routes->get('edit/(:num)', 'MenuController::edit/$1');
        $routes->post('update/(:num)', 'MenuController::update/$1');
    });

    // Product management
    $routes->group('products', function ($routes) {
        $routes->get('/', 'ProductController::index');
        $routes->get('create', 'ProductController::create');
        $routes->post('store', 'ProductController::store');
        $routes->get('view/(:num)', 'ProductController::view/$1');
        $routes->get('edit/(:num)', 'ProductController::edit/$1');
        $routes->post('update/(:num)', 'ProductController::update/$1');
        $routes->get('delete/(:num)', 'ProductController::delete/$1');
    });

    // Tenant management
    $routes->group('tenants', function ($routes) {
        $routes->get('/', 'TenantsController::index');
        $routes->get('create', 'TenantsController::create');
        $routes->post('store', 'TenantsController::store');
        $routes->get('view/(:num)', 'TenantsController::view/$1');
        $routes->get('edit/(:num)', 'TenantsController::edit/$1');
        $routes->post('update/(:num)', 'TenantsController::update/$1');
    });

    // Service management
    $routes->group('services', function ($routes) {
        $routes->get('/', 'ServiceController::index');
        $routes->get('create', 'ServiceController::create');
        $routes->post('store', 'ServiceController::store');
        $routes->get('view/(:num)', 'ServiceController::view/$1');
        $routes->get('edit/(:num)', 'ServiceController::edit/$1');
        $routes->post('update/(:num)', 'ServiceController::update/$1');
    });

    // Booking management
    $routes->group('bookings', function ($routes) {
        $routes->get('/', 'BookingController::index');
        $routes->get('create', 'BookingController::create');
        $routes->post('store', 'BookingController::store');
        $routes->get('view/(:num)', 'BookingController::view/$1');
        $routes->get('cancel/(:num)', 'BookingController::cancel/$1');
        $routes->get('payment/(:num)', 'BookingController::payment/$1');
    });

    // Schedule management
    $routes->group('schedules', function ($routes) {
        $routes->get('/', 'ScheduleController::index');
        $routes->get('create', 'ScheduleController::create');
        $routes->post('store', 'ScheduleController::store');
        $routes->get('edit/(:num)', 'ScheduleController::edit/$1');
        $routes->post('update/(:num)', 'ScheduleController::update/$1');
        $routes->get('special', 'ScheduleController::special');
        $routes->post('special/store', 'ScheduleController::storeSpecial');
    });

    // Onboarding process
    $routes->group('onboarding', function ($routes) {
        $routes->get('setup-tenant', 'OnboardingController::setupTenant');
        $routes->post('create-tenant', 'OnboardingController::createTenant');
        $routes->get('setup-branding/(:num)', 'OnboardingController::setupBranding/$1');
        $routes->post('update-branding/(:num)', 'OnboardingController::updateBranding/$1');
    });

    // API routes
    $routes->group('api', function ($routes) {
        $routes->get('slots/available/(:num)', 'Api\TimeSlotController::getAvailableSlots/$1');
    });
});

// Debug routes (admin only)
$routes->group('debug', ['filter' => ['auth', 'role:admin']], function ($routes) {
    $routes->get('test-google-registration', 'DebugController::testGoogleRegistration');
    $routes->get('check-guid-values', 'DebugController::checkGuidValues');
});

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Disable auto routing for security
$routes->setAutoRoute(false);