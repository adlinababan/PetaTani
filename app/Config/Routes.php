<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'PageController::home');
$routes->get('/tentang', 'PageController::tentang');
$routes->get('/login', 'Auth::login');
$routes->post('/do-login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Dashboard::index');

    $routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/produk', 'Produk::index');
	$routes->post('/produk', 'Produk::index');
    $routes->get('/produk/create', 'Produk::create');
    $routes->post('/produk/store', 'Produk::store');
    $routes->get('/produk/edit/(:num)', 'Produk::edit/$1');
    $routes->post('/produk/update/(:num)', 'Produk::update/$1');
    $routes->get('/produk/delete/(:num)', 'Produk::delete/$1');
});

$routes->get('/kategori', 'Kategori::index');
$routes->get('/kategori/create', 'Kategori::create');
$routes->post('/kategori/store', 'Kategori::store');
$routes->get('/kategori/edit/(:num)', 'Kategori::edit/$1');
$routes->post('/kategori/update/(:num)', 'Kategori::update/$1');
$routes->get('/kategori/delete/(:num)', 'Kategori::delete/$1');

$routes->get('/pengguna', 'Pengguna::index');
$routes->get('/pengguna/create', 'Pengguna::create');
$routes->post('/pengguna/store', 'Pengguna::store');
$routes->get('/pengguna/edit/(:num)', 'Pengguna::edit/$1');
$routes->post('/pengguna/update/(:num)', 'Pengguna::update/$1');
$routes->get('/pengguna/delete/(:num)', 'Pengguna::delete/$1');

$routes->get('/salesreport', 'SalesReport::index');
$routes->post('/salesreport', 'SalesReport::index');
$routes->get('/salesreportexcel', 'SalesReport::export_excel');

$routes->get('/profil', 'Profil::index/$1');
$routes->post('/profil/update/(:num)', 'Profil::update/$1');

// Halaman publik produk (dengan fitur pencarian & filter kategori)
$routes->get('/produk/detail', 'Produk::detail');

$routes->post('checkout/create', 'Checkout::create');
$routes->get('checkout/success', 'Checkout::success');
$routes->get('checkout/failed', 'Checkout::failed');
$routes->post('webhook/payment', 'Webhook::payment'); // handler callback Duitku

// app/Config/Routes.php
$routes->get('test/duitku', function () {
    $gateway = new \App\Services\Gateways\DuitkuGateway();
    $orderRef = 'TEST-' . bin2hex(random_bytes(4));
    $session = $gateway->createPaymentSession([
        'order_ref' => $orderRef,
        'amount'    => 10000,  // Rp10.000
        'productDetails' => 'Tes Invoice',
        'customer'  => ['firstName'=>'Tester','lastName'=>'','email'=>'test@example.com','phone'=>'62811111111'],
        'billing'   => ['firstName'=>'Tester','lastName'=>'','address'=>'Jl. Test','city'=>'Jakarta','postalCode'=>'10000','phone'=>'62811111111','countryCode'=>'ID'],
        'shipping'  => ['firstName'=>'Tester','lastName'=>'','address'=>'Jl. Test','city'=>'Jakarta','postalCode'=>'10000','phone'=>'62811111111','countryCode'=>'ID'],
        'callback'  => rtrim(getenv('APP_BASE_URL') ?: base_url(), '/') . '/webhook/payment',
        'success'   => rtrim(getenv('APP_BASE_URL') ?: base_url(), '/') . '/checkout/success?order=' . $orderRef,
        'failed'    => rtrim(getenv('APP_BASE_URL') ?: base_url(), '/') . '/checkout/failed?order=' . $orderRef,
    ]);
    return redirect()->to($session['pay_url']);
});








