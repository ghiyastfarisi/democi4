<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [ 'InternalHttpResponse', 'InternalTemplate', 'InternalSecurity', 'html' ];
	protected $db;
	protected $session;

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);

		$this->db = \Config\Database::connect();
		$this->session = \Config\Services::session();
	}

	public function GetSession()
	{
		return $this->session->get('auth');
	}

	public function SetSession($data)
	{
		return $this->session->set('auth', $data);
	}

	public function DestroySession()
	{
		return $this->session->destroy();
	}

	public function IsLoggedIn()
	{
		$gs = $this->GetSession();

		if (!isset($gs)) {
			return false;
		}

		if (isset($gs['login']) && $gs['login'] == true) {
			return true;
		}

		return false;
	}
}
