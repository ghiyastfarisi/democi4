<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// api routing
// sample api
$routes->group('api', function($routes)
{
	$routes->get('v1/country', 'Api\V1\Country::Get');
	$routes->get('v1/location', 'Api\V1\Location::Get');
	$routes->get('v1/produk', 'Api\V1\Produk::Get');
	$routes->group('v1/user', function($routes)
	{
		$routes->get('all', 'Api\V1\User::GetAll');
		$routes->get('(:num)', 'Api\V1\User::GetById/$1');
		$routes->post('create', 'Api\V1\User::create');
		$routes->post('create/pembina-mutu', 'Api\V1\User::createPembinaMutu');
		$routes->patch('update/(:num)', 'Api\V1\User::update/$1');
		$routes->delete('(:num)', 'Api\V1\User::delete/$1');
	});
	$routes->group('v1/pembina-mutu', function($routes)
	{
		$routes->get('all', 'Api\V1\PembinaMutu::GetAll');
		$routes->get('user/(:num)', 'Api\V1\PembinaMutu::GetByUser/$1');
		$routes->post('create/user/(:num)', 'Api\V1\PembinaMutu::create/$1');
		$routes->get('(:num)', 'Api\V1\PembinaMutu::GetById/$1');
		$routes->patch('update/(:num)', 'Api\V1\PembinaMutu::update/$1');
	});
	$routes->group('v1/pendidikan', function($routes)
	{
		$routes->get('pembina-mutu', 'Api\V1\PembinaMutuPendidikan::GetAll');
		$routes->post('create/pembina-mutu/(:num)', 'Api\V1\PembinaMutuPendidikan::create/$1');
		$routes->delete('(:num)', 'Api\V1\PembinaMutuPendidikan::delete/$1');
		$routes->get('(:num)', 'Api\V1\PembinaMutuPendidikan::GetById/$1');
		$routes->patch('update/(:num)', 'Api\V1\PembinaMutuPendidikan::update/$1');
	});
	$routes->group('v1/jabatan', function($routes)
	{
		$routes->get('pembina-mutu', 'Api\V1\PembinaMutuJabatan::GetAll');
		$routes->post('create/pembina-mutu/(:num)', 'Api\V1\PembinaMutuJabatan::create/$1');
		$routes->delete('(:num)', 'Api\V1\PembinaMutuJabatan::delete/$1');
		$routes->get('(:num)', 'Api\V1\PembinaMutuJabatan::GetById/$1');
		$routes->patch('update/(:num)', 'Api\V1\PembinaMutuJabatan::update/$1');
	});
	$routes->group('v1/pelatihan', function($routes)
	{
		$routes->get('pembina-mutu', 'Api\V1\PembinaMutuPelatihan::GetAll');
		$routes->post('create/pembina-mutu/(:num)', 'Api\V1\PembinaMutuPelatihan::create/$1');
		$routes->delete('(:num)', 'Api\V1\PembinaMutuPelatihan::delete/$1');
		$routes->get('(:num)', 'Api\V1\PembinaMutuPelatihan::GetById/$1');
		$routes->patch('update/(:num)', 'Api\V1\PembinaMutuPelatihan::update/$1');
	});
	$routes->group('v1/upi', function($routes)
	{
		$routes->get('all', 'Api\V1\Upi::GetAll');
		$routes->get('(:num)', 'Api\V1\Upi::GetById/$1');
		$routes->get('(:num)/complete', 'Api\V1\Upi::GetCompleteById/$1');
		$routes->get('search', 'Api\V1\Upi::Search');
		$routes->post('create/complete', 'Api\V1\Upi::createComplete');
		$routes->patch('(:num)/update/complete', 'Api\V1\Upi::updateComplete/$1');
		$routes->post('(:num)/request/perubahan-upi', 'Api\V1\Upi::requestUpdate/$1');
		$routes->post('update/perubahan-upi/(:num)/(:alpha)', 'Api\V1\Upi::requestUpdatePerubahaUpi/$1/$2');
		$routes->get('all/perubahan-upi', 'Api\V1\Upi::GetAllPerubahan');
	});
	$routes->group('v1/produksi', function($routes)
	{
		$routes->get('upi/(:num)', 'Api\V1\UpiProduksi::GetByUpi/$1');
	});
	$routes->group('v1/badge', function($routes)
	{
		$routes->get('all', 'Api\V1\Badge::Get');
	});
	$routes->group('v1/tenaga-kerja', function($routes)
	{
		$routes->get('upi/(:num)', 'Api\V1\UpiTenagaKerja::GetByUpi/$1');
	});
	$routes->group('v1/sarpras', function($routes)
	{
		$routes->get('upi/(:num)', 'Api\V1\UpiSarpras::GetByUpi/$1');
	});
	$routes->group('v1/kunjungan', function($routes)
	{
		$routes->get('all', 'Api\V1\Kunjungan::GetAll');
		$routes->get('(:num)', 'Api\V1\Kunjungan::GetById/$1');
		$routes->post('create', 'Api\V1\Kunjungan::create');
		$routes->patch('update/(:num)', 'Api\V1\Kunjungan::update/$1');
		$routes->delete('(:num)', 'Api\V1\Kunjungan::delete/$1');
		$routes->get('(:num)/file', 'Api\V1\Kunjungan::GetFileByKunjunganId/$1');
		$routes->delete('file/(:num)', 'Api\V1\Kunjungan::deleteFile/$1');
	});
	$routes->group('v1/perubahan-upi', function($routes)
	{
		$routes->get('all', 'Api\V1\PerubahanUpi::GetAll');
		$routes->get('(:num)', 'Api\V1\PerubahanUpi::GetById/$1');
	});
	$routes->group('v1/upload', function($routes)
	{
		$routes->post('file', 'Api\V1\Upload::file');
	});
});

$routes->group('web', function($routes)
{
	$routes->get('pembina-mutu', 'Web\PembinaMutu::index');
	$routes->get('pembina-mutu/get/(:num)', 'Web\PembinaMutu::get/$1');
}
);

$routes->post('login', 'Api\V1\User::login');
$routes->post('register', 'Api\V1\User::register');
$routes->get('logout', 'Auth::logout');


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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
