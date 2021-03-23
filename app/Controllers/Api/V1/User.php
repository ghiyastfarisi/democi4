<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
	protected $UserModel;
	protected $validation;

	function __construct()
	{
		$this->UserModel = new UserModel();
		$this->validation = \Config\Services::validation();
	}

	public function index()
	{
		return 'api user v1 here...';
	}

	public function GetAll()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();

		$skip = (isset($q['skip'])) ? $q['skip'] : 0;
		$limit = (isset($q['limit'])) ? $q['limit'] : 1;
		$offset = $skip*$limit;

		$resp = $this->UserModel->findAll($limit, $offset);
		$count = $this->UserModel->selectCount('id')->find();

		$transformed = array(
			'queries' 		=> array(
				'limit'		=> (int)$limit,
				'skip'		=> (int)$skip
			),
			'data' 			=> $resp,
			'pagination'	=> array(
				'total' 	=> (int)$count[0]['id'],
				'next'		=> ($skip > 0) ? $skip + 1 : 1,
				'prev'		=> ($skip > 0) ? $skip - 1 : 0,
			)
		);

		return ResponseOK($transformed);
	}

	public function GetById($id = null)
	{
		$req = $this->request;

		if (null === $id) {
			return ResponseNotFound();
		}

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$resp = $this->UserModel->find($id);

		return ResponseOK($resp);
	}

	public function create()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$this->validation->setRules([
			'username' => 'required|min_length[3]|valid_email',
			'password' => 'required|min_length[8]'
		], [
			'username' => [
				'required' 		=> 'wajib diisi',
				'valid_email'	=> 'harap gunakan format email'
			],
			'password' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 8 karakter'
			]
		]);

		$reqArray = (array) $req->getJSON();

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$foundRecord = $this->UserModel->where('username', $reqArray['username'])->selectCount('id')->find();

		if (count($foundRecord) > 0 && (int)$foundRecord[0]['id'] > 0) {
			return ResponseConflict(array('message' => 'username already registered'));
		}

		$insert = array_merge(
			$reqArray,
			array(
				'login_status' 			=> 'inactive',
				'registration_token' 	=> hash('sha256', 'registration_token'.sha1($reqArray['username'].microtime())),
				'generated_token' 		=> hash('sha256', 'generated_token'.sha1($reqArray['username'].microtime()))
			)
		);

		$resp = $this->UserModel->save($insert);

		return ResponseCreated(array( 'message' => 'user created' ));
	}

	public function update($id = 0)
	{
		if ($id < 1) {
			return ResponseNotFound();
		}

		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'PATCH') {
			return ResponseNotAllowed();
		}

		$vRulesConfig = array(
			'username' 		=> 'min_length[3]|valid_email',
			'password' 		=> 'min_length[8]',
			'login_status' 	=> 'in_list[active,inactive]'
		);
		$vMessagesConfig = array(
			'username' => [
				'valid_email'	=> 'harap gunakan format email'
			],
			'password' => [
				'min_length' 	=> 'minimal 8 karakter'
			],
			'login_status' => [
				'in_list'		=> 'tidak terdaftar'
			]
		);

		$reqArray = (array) $req->getJSON();

		$validationSetRules = array();
		$validationSetMessages = array();

		foreach ($reqArray as $key => $val) {
			if ($vRulesConfig[$key] !== null && $vMessagesConfig[$key] !== null) {
				$validationSetRules[$key] = $vRulesConfig[$key];
				$validationSetMessages[$key] = $vMessagesConfig[$key];
			}
		}

		$this->validation->setRules($validationSetRules, $validationSetMessages);

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		if (isset($reqArray['username'])) {
			$foundRecord = $this->UserModel->where('username', $reqArray['username'])->selectCount('id')->find($id);

			if (count($foundRecord) > 0 && (int)$foundRecord[0]['id'] > 0) {
				return ResponseConflict(array('message' => 'username already registered'));
			}
		}

		$reqArray['id'] = $id;

		$resp = $this->UserModel->save($reqArray);

		return ResponseOK(array( 'message' => 'user updated' ));
	}
}
