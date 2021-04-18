<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UpiModel;
use App\Models\UpiFotoProdukModel;
use App\Models\UpiTenagaKerjaModel;

class Upi extends BaseController
{
	protected $UpiModel;
	protected $validation;

	function __construct()
	{
		$this->UpiModel = new UpiModel();
		$this->validation = \Config\Services::validation();
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

		$resp = $this->UpiModel->orderBy('id', 'desc')->findAll($limit, $offset);
		$countQuery = $this->UpiModel->selectCount('id')->find();
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

		$resp = $this->UpiModel->find($id);

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

		$foundRecord = $this->UpiModel->where('username', $reqArray['username'])->selectCount('id')->find();

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

		$resp = $this->UpiModel->save($insert);

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
			$foundRecord = $this->UpiModel->where('username', $reqArray['username'])->selectCount('id')->find($id);

			if (count($foundRecord) > 0 && (int)$foundRecord[0]['id'] > 0) {
				return ResponseConflict(array('message' => 'username already registered'));
			}
		}

		$reqArray['id'] = $id;

		$resp = $this->UpiModel->save($reqArray);

		return ResponseOK(array( 'message' => 'user updated' ));
	}

	public function delete($id = 0) {
		if ($id < 1) {
			return ResponseNotFound();
		}

		$deleted = $this->UpiModel->delete($id);

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

		$foundRecord = $this->UpiModel->where('username', $reqArray['username'])->selectCount('id')->find();

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

		$saved = $this->UpiModel->save($insert);

		if ($saved) {
			$puModel = new PembinaMutuModel();

			$user_id = $this->UpiModel->getInsertID();

			$insertPembinaMutu = array_merge(
				$reqArray,
				array(
					'user_id' => (int)$user_id
				)
			);

			$puModel->save($insertPembinaMutu);
		}

		return ResponseCreated(array( 'message' => 'user and pembina mutu created' ));
	}
}
