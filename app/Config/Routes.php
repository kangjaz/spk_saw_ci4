<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::proses_login');
$routes->get('/dashboard', 'Home::index');
$routes->get('/logout', 'Home::logout');
$routes->get('/profil', 'Home::profil');
$routes->post('/profil', 'Home::update_profil');
$routes->get('/change-password', 'Home::change_password');
$routes->post('/change-password', 'Home::update_password');

/* --------------------------------------------------------------------
 * Alternative Routes
 * ------------------------------------------------------------------ */
$routes->get('/alternative', 'Alternative::index');
$routes->post('/alternative', 'Alternative::simpan');
$routes->post('/alternative/get', 'Alternative::getData');
$routes->post('/alternative/ajax-list', 'Alternative::ajaxList');
$routes->post('/alternative/delete', 'Alternative::delete');
$routes->post('/alternative/(:any)', 'Alternative::update/$1');

/* --------------------------------------------------------------------
 * Kriteria Routes
 * ------------------------------------------------------------------ */
$routes->get('/kriteria', 'Kriteria::index');
$routes->post('/kriteria', 'Kriteria::simpan');
$routes->post('/kriteria/get', 'Kriteria::getData');
$routes->post('/kriteria/ajax-list', 'Kriteria::ajaxList');
$routes->post('/kriteria/delete', 'Kriteria::delete');
$routes->post('/kriteria/(:any)', 'Kriteria::update/$1');

/* --------------------------------------------------------------------
 * Sub Kriteria Routes
 * ----------------------------------------------------------------- */
$routes->get('/sub-kriteria', 'SubKriteria::index');
$routes->post('/sub-kriteria', 'SubKriteria::simpan');
$routes->post('/sub-kriteria/get', 'SubKriteria::getData');
$routes->post('/sub-kriteria/ajax-list', 'SubKriteria::ajaxList');
$routes->post('/sub-kriteria/delete', 'SubKriteria::delete');
$routes->post('/sub-kriteria/(:any)', 'SubKriteria::update/$1');

/* -------------------------------------------------------------------
 * Bobot Routes
 * ---------------------------------------------------------------- */
$routes->get('/bobot', 'Bobot::index');
$routes->post('/bobot', 'Bobot::Simpan');

/* -------------------------------------------------------------------
 * Penilaian Routes
 * ---------------------------------------------------------------- */
$routes->get('/penilaian', 'Penilaian::index');
$routes->post('/penilaian', 'Penilaian::simpan');
$routes->post('/penilaian/get-detail', 'Penilaian::getDetail');
$routes->post('/penilaian/get', 'Penilaian::getData');
$routes->post('/penilaian/delete', 'Penilaian::delete');
$routes->post('/penilaian/(:any)', 'Penilaian::update/$1');

/* -------------------------------------------------------------------
 * Hasil Routes
 * ---------------------------------------------------------------- */
$routes->get('/hasil', 'Hasil::index');
$routes->get('/cetak-hasil', 'Hasil::cetak');

/* -------------------------------------------------------------------
 * Manajemen User Routes
 * ---------------------------------------------------------------- */
$routes->get('/user', 'User::index');
$routes->get('/user/add', 'User::add');
$routes->post('/user/add', 'User::simpan');
$routes->post('/user/ajax-list', 'User::ajaxList');
$routes->post('/user/delete', 'User::delete');
$routes->get('/user/(:any)', 'User::edit/$1');
$routes->post('/user/(:any)', 'User::update/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
