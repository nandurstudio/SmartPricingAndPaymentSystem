<?php

namespace Config;

use Config\Services;

$routes = Services::routes();

// Default route
// Menambahkan route untuk halaman default (landing) setelah login
$routes->get('/', 'Auth::landingPage', ['filter' => 'auth']);
$routes->get('landing', 'Auth::landingPage', ['filter' => 'auth']);  // Pastikan ada route ini

// Route untuk login
$routes->get('login', 'Auth::index');  // Menampilkan halaman login
$routes->post('login', 'Auth::login');  // Mengirim data login
$routes->get('logout', 'Auth::logout');  // Mengeluarkan pengguna

// Route untuk register
$routes->get('/register', 'Register::index');  // Menampilkan halaman registrasi
$routes->post('/register/createUser', 'Register::createUser');  // Mengirim data registrasi

// Route untuk forgot password
$routes->get('auth/forgot_password', 'Auth::forgotPassword');  // Menampilkan halaman lupa password
$routes->post('auth/sendResetLink', 'Auth::sendResetLink');  // Mengirim link reset password
$routes->get('auth/reset_password/(:any)', 'Auth::resetPassword/$1');  // Menampilkan halaman reset password
$routes->post('auth/updatePassword', 'Auth::updatePassword');  // Memperbarui password

// Route untuk master Parameter dengan grouping dan filter auth
$routes->group('parameter', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Parameter::index');  // Menampilkan daftar parameter
    $routes->get('create', 'Parameter::create');  // Menampilkan form untuk membuat parameter baru
    $routes->post('store', 'Parameter::store');  // Menyimpan parameter baru ke database
    $routes->get('edit/(:num)', 'Parameter::edit/$1');  // Menampilkan form untuk mengedit parameter berdasarkan ID
    $routes->post('update/(:num)', 'Parameter::update/$1');  // Memperbarui parameter berdasarkan ID
    $routes->get('view/(:num)', 'Parameter::view/$1');  // Menampilkan detail parameter berdasarkan ID
});

// Route untuk master Tool dengan grouping dan filter auth
$routes->group('tool', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Tool::index');  // Menampilkan daftar tool
    $routes->get('create', 'Tool::create');  // Menampilkan form untuk membuat tool baru
    $routes->post('store', 'Tool::store');  // Menyimpan tool baru ke database
    $routes->get('edit/(:num)', 'Tool::edit/$1');  // Menampilkan form untuk mengedit tool berdasarkan ID
    $routes->post('update/(:num)', 'Tool::update/$1');  // Memperbarui tool berdasarkan ID
    $routes->get('view/(:num)', 'Tool::view/$1');  // Menampilkan detail tool berdasarkan ID
});

// Route untuk Job Title dengan grouping dan filter auth
$routes->group('job_title', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'JobTitleController::index');  // Menampilkan daftar job titles
    $routes->get('create', 'JobTitleController::create');  // Menampilkan form untuk membuat job title
    $routes->post('store', 'JobTitleController::store');  // Menyimpan job title baru
    $routes->get('edit/(:num)', 'JobTitleController::edit/$1');  // Menampilkan form untuk edit job title
    $routes->post('update/(:num)', 'JobTitleController::update/$1');  // Memperbarui job title
    $routes->get('view/(:num)', 'JobTitleController::view/$1');  // Menampilkan detail job title
});

// Grouping untuk Competency dengan filter auth
$routes->group('competency', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CompetencyController::index');  // Menampilkan daftar competencies
    $routes->get('create', 'CompetencyController::create');  // Menampilkan form create
    $routes->post('store', 'CompetencyController::store');  // Menyimpan competency baru
    $routes->get('edit/(:num)/(:num)', 'CompetencyController::edit/$1/$2');  // Menampilkan form edit dengan dua parameter
    $routes->post('update/(:num)/(:num)', 'CompetencyController::update/$1/$2');  // Menyimpan perubahan pada competency
    $routes->get('view/(:num)/(:num)', 'CompetencyController::view/$1/$2');  // Menampilkan detail competency
    // Tambahkan rute baru untuk mendapatkan data competency
    $routes->post('getCompetencies', 'CompetencyController::getCompetencies'); // Endpoint untuk DataTables
});

// Grouping untuk Competency dengan filter auth
$routes->group('competencies', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CompetenciesController::index');  // Menampilkan daftar competencies
    $routes->get('create', 'CompetenciesController::create');  // Menampilkan form create
    $routes->post('store', 'CompetenciesController::store');  // Menyimpan competency baru
    $routes->get('edit/(:num)', 'CompetenciesController::edit/$1');  // Menampilkan form edit dengan satu parameter
    $routes->post('update/(:num)', 'CompetenciesController::update/$1');  // Menyimpan perubahan pada competency
    $routes->get('view/(:num)', 'CompetenciesController::view/$1');  // Menampilkan detail competency
    $routes->post('getCompetencies', 'CompetenciesController::getCompetencies'); // Endpoint untuk DataTables
});

// Grouping untuk Competency Progress dengan filter auth
$routes->group('competency_progress', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'CompetencyProgressController::index');  // Menampilkan daftar progress competency
    $routes->get('create', 'CompetencyProgressController::create');  // Menampilkan form create
    $routes->post('store', 'CompetencyProgressController::store');  // Menyimpan progress baru
    $routes->get('edit/(:num)', 'CompetencyProgressController::edit/$1');  // Menampilkan form edit
    $routes->post('update/(:num)', 'CompetencyProgressController::update/$1');  // Menyimpan perubahan
    $routes->get('view/(:num)', 'CompetencyProgressController::view/$1');  // Menampilkan detail
    $routes->get('delete/(:num)', 'CompetencyProgressController::delete/$1'); // Menghapus progress
    $routes->get('get_indicators/(:num)/(:num)', 'CompetencyProgressController::getIndicators/$1/$2');
});

$routes->group('user', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'UserController::index');                 // Daftar user
    $routes->get('create', 'UserController::create');           // Form tambah user baru
    $routes->post('create', 'UserController::store');           // Proses simpan user baru
    $routes->get('view/(:num)', 'UserController::view/$1');     // Lihat detail user
    $routes->get('update/(:num)', 'UserController::edit/$1');   // Form edit user
    $routes->post('update/(:num)', 'UserController::update/$1'); // Proses update user
    $routes->post('getUsers', 'UserController::getUsers'); // Endpoint untuk DataTables
});

$routes->group('line', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'LineController::index');
    $routes->get('create', 'LineController::create');
    $routes->post('store', 'LineController::store');
    $routes->get('edit/(:num)', 'LineController::edit/$1');
    $routes->post('update/(:num)', 'LineController::update/$1');
    $routes->get('detail/(:num)', 'LineController::detail/$1'); // Rute detail
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

// Group routes for UserJobTitleCompetencyIndicator with auth filter
$routes->group('UserJobTitleCompetencyIndicator', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'UserJobTitleCompetencyIndicatorController::index'); // Menampilkan daftar indikator
    $routes->get('create', 'UserJobTitleCompetencyIndicatorController::create'); // Form untuk membuat indikator baru
    $routes->post('store', 'UserJobTitleCompetencyIndicatorController::store'); // Menyimpan indikator baru
    $routes->get('view/(:num)', 'UserJobTitleCompetencyIndicatorController::view/$1'); // Menampilkan detail indikator
    $routes->get('edit/(:num)', 'UserJobTitleCompetencyIndicatorController::edit/$1'); // Form untuk edit indikator
    $routes->post('update/(:num)', 'UserJobTitleCompetencyIndicatorController::update/$1'); // Memperbarui indikator

    // Rute untuk mendapatkan kompetensi berdasarkan job title
    $routes->get('getCompetenciesByJobTitle/(:num)', 'UserJobTitleCompetencyIndicatorController::getCompetenciesByJobTitle/$1');

    // Rute untuk mendapatkan indikator berdasarkan kompetensi
    $routes->get('getIndicatorsByCompetency/(:num)', 'UserJobTitleCompetencyIndicatorController::getIndicatorsByCompetency/$1');
    $routes->post('UserJobTitleCompetencyIndicator/delete/(:num)', 'UserJobTitleCompetencyIndicatorController::delete/$1');
});

$routes->post('indicators/getIndicatorsByCompetency', 'IndicatorController::getIndicatorsByCompetency');
$routes->post('indicators/getAllIndicatorsByCompetency', 'IndicatorController::getIndicatorsByCompetency');

$routes->group('auto_suggest', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'AutoSuggestController::index'); // Route to index view
    $routes->post('search', 'AutoSuggestController::search'); // Route for search
    $routes->get('job-title-indicators', 'AutoSuggestController::jobTitleIndicators'); // Optional route for job title indicators
});

$routes->group('transactions', ['filter' => 'auth'], function ($routes) {
    $routes->get('user_jobtitle/auto_suggest/', 'UserJobTitleController::auto_suggest');
    $routes->get('user_jobtitle', 'UserJobTitleController::index');
    $routes->get('user_jobtitle/create', 'UserJobTitleController::create');
    $routes->post('user_jobtitle/store', 'UserJobTitleController::store');
    $routes->get('user_jobtitle/details/(:num)', 'UserJobTitleController::details/$1');
    $routes->get('user_jobtitle/edit/(:num)', 'UserJobTitleController::edit/$1');
    $routes->post('user_jobtitle/update', 'UserJobTitleController::update');
    $routes->post('getUserJobTitles', 'UserJobTitleController::getUserJobTitles');
    $routes->post('getUserJobTitlesAutoSuggest', 'UserJobTitleController::getUserJobTitlesAutoSuggest');
});

// Grouping untuk Department dengan filter auth
$routes->group('departments', ['filter' => 'auth'], function ($routes) {
    $routes->get('getDepartments', 'DepartmentController::getDepartments');
});

$routes->group('functionalcompetency', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'FunctionalCompetencyController::index');  // Menampilkan daftar
    $routes->get('create', 'FunctionalCompetencyController::create');  // Menampilkan form untuk membuat data baru
    $routes->post('store', 'FunctionalCompetencyController::store');  // Menyimpan data baru ke database
    $routes->get('edit/(:num)', 'FunctionalCompetencyController::edit/$1');  // Menampilkan form untuk mengedit data berdasarkan ID
    $routes->post('update/(:num)', 'FunctionalCompetencyController::update/$1');  // Memperbarui data berdasarkan ID
    $routes->get('view/(:num)', 'FunctionalCompetencyController::view/$1');  // Menampilkan detail berdasarkan ID
    $routes->get('getCompetencyDefinition/(:num)', 'FunctionalCompetencyController::getCompetencyDefinition/$1');  // Menampilkan getCompetencyDefinition berdasarkan ID
    $routes->get('getIndicators/(:num)', 'FunctionalCompetencyController::getIndicators/$1');  // Menampilkan getIndicators berdasarkan ID
    $routes->get('getCompetencies/(:num)', 'FunctionalCompetencyController::getCompetencies/$1');  // Menampilkan getCompetencies berdasarkan ID
});

// Grouping untuk Assessment dengan filter auth
$routes->group('assessment', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AssessmentController::index');  // Menampilkan daftar assessment
    $routes->get('create', 'AssessmentController::create');  // Menampilkan form create
    $routes->post('store', 'AssessmentController::store');  // Menyimpan assessment baru
    $routes->get('edit/(:num)', 'AssessmentController::edit/$1');  // Menampilkan form edit
    $routes->get('view/(:num)', 'AssessmentController::view/$1');  // Menampilkan detail
    $routes->get('progress/(:num)/(:num)', 'AssessmentController::getProgress/$1/$2'); // Mendapatkan progress
    $routes->post('update/(:num)', 'AssessmentController::update/$1');
    $routes->delete('delete/(:num)', 'AssessmentController::delete/$1');  // DELETE untuk menghapus
});
