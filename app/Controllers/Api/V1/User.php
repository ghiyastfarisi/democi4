<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PembinaMutuModel;

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

		$page = (isset($q['page'])) ? $q['page'] : 1;
		$limit = (isset($q['limit'])) ? $q['limit'] : 1;
		$offset = ($page - 1) * $limit;

		$resp = $this->UserModel->orderBy('id', 'desc')->findAll($limit, $offset);
		$countQuery = $this->UserModel->selectCount('id')->find();
		$count = (int)$countQuery[0]['id'];

		$pageAvailable = ceil((int)$count/(int)$limit);

		$current = $page;
		$next = ($pageAvailable <= $current) ? 0 : $page + 1;
		$prev = ($page == 1) ? 0 : $page - 1;

		$lists = [];

		if (count($resp) > 0) {
			$lists = [ (int)$current ];

			if ($prev > 0) {
				$limitPrev = ($next == 0) ? $prev-1 : $prev;
				for ($i = $current - 1; $i >= $limitPrev; $i--) {
					if ($i >= 1) {
						array_unshift($lists, (int)$i);
					}
				}
			}

			if ($next > 0) {
				$limitNext = ($prev == 0) ? $next+1 : $next;
				for ($i = $current + 1; $i <= $limitNext; $i++) {
					if ($i <= $pageAvailable) {
						array_push($lists, (int)$i);
					}
				}
			}
		}


		$transformed = array(
			'queries' 		=> array(
				'limit'		=> (int)$limit,
				'page'		=> (int)$page
			),
			'data' 			=> $resp,
			'pagination'	=> array(
				'total' 	=> $count,
				'current'	=> (int)$current,
				'next'		=> $next,
				'prev'		=> $prev,
				'list'		=> $lists
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

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
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

		$this->UserModel->save($insert);

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

		// check old id and generated token
		$user = $this->UserModel->find($id);

		if (!isset($user)) {
			return ResponseError(400, array('message' => 'user invalid'));
		}

		$reqArray['id'] = $id;
		$reqArray['generated_token'] = $user['generated_token'];

		$this->UserModel->save($reqArray);

		return ResponseOK(array( 'message' => 'user updated' ));
	}

	public function delete($id = 0) {
		if ($id < 1) {
			return ResponseNotFound();
		}

		$deleted = $this->UserModel->delete($id);

		return ResponseOK(array( 'message' => 'user deleted' ));
	}

	public function createPembinaMutu() {
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$vRules = array(
			'username' 		=> 'required|min_length[3]|valid_email',
			'password' 		=> 'required|min_length[8]',
			'nama_lengkap' 	=> 'required|min_length[3]',
			'nip' 			=> 'required|min_length[11]',
			'no_hp'			=> 'required|min_length[10]'
		);
		$vMessages = array(
			'username' => [
				'required' 		=> 'wajib diisi',
				'valid_email'	=> 'harap gunakan format email'
			],
			'password' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 8 karakter'
			],
			'nama_lengkap' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 3 karakter'
			],
			'nip' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 11 karakter'
			],
			'foto_profil' => [
				'min_length' 	=> 'minimal 3 karakter',
				'valid_url'		=> 'harap isi dengan url gambar yang benar'
			],
			'keahlian' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 3 karakter'
			],
			'deskripsi' => [
				'min_length' 	=> 'minimal 3 karakter'
			],
			'no_hp' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 10 karakter'
			]
		);

		$reqArray = (array)$req->getJSON();

		if (isset($reqArray['foto_profil'])) {
			$vRules['foto_profil'] = 'min_length[3]|valid_url';
		}

		if (isset($reqArray['deskripsi'])) {
			$vRules['deskripsi'] = 'min_length[5]';
		}


		$this->validation->setRules($vRules, $vMessages);

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

		$saved = $this->UserModel->save($insert);

		if ($saved) {
			$puModel = new PembinaMutuModel();

			$user_id = $this->UserModel->getInsertID();

			$insertPembinaMutu = array_merge(
				$reqArray,
				array(
					'user_id' 	=> (int)$user_id,
					'email'		=> $reqArray['username']
				)
			);

			$puModel->save($insertPembinaMutu);
		}

		return ResponseCreated(array( 'message' => 'user and pembina mutu created' ));
	}

	public function register()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		return $this->createPembinaMutu();
	}

	public function login()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$this->validation->setRules([
			'username' => 'required|valid_email',
			'password' => 'required'
		], [
			'username' => [
				'required' 		=> 'wajib diisi',
				'valid_email'	=> 'harap gunakan format email'
			],
			'password' => [
				'required' 		=> 'wajib diisi'
			]
		]);

		$reqArray = (array) $req->getJSON();

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$foundRecord = $this->UserModel
			->select('tbl_user.*, tbl_pembina_mutu.id as pmid')
			->join('tbl_pembina_mutu', 'tbl_pembina_mutu.user_id = tbl_user.id')
			->where('tbl_user.username', $reqArray['username'])->get()->getRow();

		if (null == $foundRecord || (int)$foundRecord->id == 0) {
			return ResponseError(403, array('message' => 'user belum terdaftar'));
		}

		if ($foundRecord->login_status!=='active')
		{
			return ResponseError(403, array('message' => 'user belum aktif'));
		}

		$pass = $reqArray['password'].$foundRecord->generated_token;

		$valid = password_verify($pass, $foundRecord->password);

		if (!$valid) {
			return ResponseError(403, array('message' => 'email atau password salah'));
		}

		$session = array(
            'login'     => true,
            'uid'       => $foundRecord->id,
            'pmid'      => $foundRecord->pmid,
            'username'  => $foundRecord->username,
            'role'      => $foundRecord->role,
        );

		$this->SetSession($session);

		return ResponseOK(array( 'data' => $session  ));
	}
}
