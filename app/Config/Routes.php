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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
