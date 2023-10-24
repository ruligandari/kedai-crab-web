<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth\AuthController::index');
$routes->get('logout', 'Auth\AuthController::logout');
$routes->post('auth', 'Auth\AuthController::auth');

$routes->group('chef', ['filter' => 'role'], static function ($routes) {
    $routes->get('dashboard', 'Chef\DashboardController::index');
    $routes->get('list-menu', 'Chef\ListMenuController::index');
    $routes->get('create-menu', 'Chef\ListMenuController::create');
    $routes->post('tambah-menu', 'Chef\ListMenuController::add');
    $routes->get('delete-menu/(:any)', 'Chef\ListMenuController::delete/$1');
    $routes->get('detail-menu/(:any)', 'Chef\ListMenuController::detail/$1');
    $routes->get('update-stok/(:any)', 'Chef\ListMenuController::stok/$1');
    $routes->post('update-menu/(:any)', 'Chef\ListMenuController::update/$1');
    $routes->get('list-pesanan', 'Chef\ListPesananController::index');
    $routes->get('detail-pesanan/(:any)', 'Chef\ListPesananController::detail/$1');
    $routes->post('update-status', 'Chef\ListPesananController::updateStatus');
});

$routes->group('kasir', ['filter' => 'role'],  static function ($routes) {
    $routes->get('dashboard', 'Kasir\DashboardController::index');
    $routes->get('transaksi', 'Kasir\TransaksiController::index');
    $routes->get('transaksi/(:any)', 'Kasir\TransaksiController::detail/$1');
    $routes->post('bayar', 'Kasir\TransaksiController::bayar');
});

$routes->group('pemilik', ['filter' => 'role'], static function ($routes) {
    $routes->get('dashboard', 'Pemilik\DashboardController::index');
    $routes->get('list-menu', 'Pemilik\ListMenuController::index');
    $routes->get('list-transaksi', 'Pemilik\ListTransaksiController::index');
    $routes->get('list-user', 'Pemilik\ListUserController::index');
    $routes->get('tambah-user', 'Pemilik\ListUserController::create');
    $routes->post('add', 'Pemilik\ListUserController::add');
    $routes->post('delete-user', 'Pemilik\ListUserController::delete');
    $routes->post('edit-user', 'Pemilik\ListUserController::update');
});
$routes->group('kurir', ['filter' => 'role'], static function ($routes) {
    $routes->get('dashboard', 'Kurir\DashboardController::index');
    $routes->post('list-antar', 'Kurir\DashboardController::listAntar');
    $routes->post('antar', 'Kurir\DashboardController::antar');
});





// RESTFul API
$routes->group('auth', function ($routes) {
    $routes->post('login', 'Api\AuthController::login');
    $routes->post('register', 'Api\AuthController::register');
});

$routes->group('api', ['filter' => 'jwtfilter'], function ($routes) {
    $routes->get('makanan', 'Api\MakananController::index');
    $routes->get('makanan/(:any)', 'Api\MakananController::detail/$1');
    $routes->post('transaksi', 'Api\TransaksiController::addTransaksi');
    $routes->get('transaksi/(:any)', 'Api\TransaksiController::getTransaksiById/$1');
    $routes->post('order', 'Api\TransaksiController::detailOrder');
    $routes->get('user/(:any)', 'Api\UserController::getUser/$1');
    $routes->post('keranjang', 'Api\KeranjangController::index');
    $routes->post('keranjang/total', 'Api\KeranjangController::totalKeranjang');
    $routes->get('keranjang', 'Api\KeranjangController::getAllKeranjang');
    $routes->get('keranjang/(:any)', 'Api\KeranjangController::getKeranjang/$1');
    $routes->post('keranjang/update', 'Api\KeranjangController::updateKeranjang');
    $routes->delete('keranjang/(:any)', 'Api\KeranjangController::delete/$1');
});
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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
