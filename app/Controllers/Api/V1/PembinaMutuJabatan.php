<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\PembinaMutuJabatanModel;

class PembinaMutuJabatan extends BaseController
{
	protected $db;
	protected $PembinaMutuJabatanModel;
	protected $validation;

	function __construct()
	{
		$this->PembinaMutuJabatanModel = new PembinaMutuJabatanModel();
		$this->validation = \Config\Services::validation();
		$this->db = \Config\Database::connect();
	}

	public function GetAll()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();

		$pembina_mutu_id = (isset($q['pembina_mutu_id'])) ? $q['pembina_mutu_id'] : 0;;
		$page = (isset($q['page'])) ? $q['page'] : 1;
		$limit = (isset($q['limit'])) ? $q['limit'] : 1;
		$offset = ($page - 1) * $limit;

		$where = ($pembina_mutu_id > 0) ? ['pembina_mutu_id' => $pembina_mutu_id] : [];

		$resp = $this->PembinaMutuJabatanModel->where($where)->orderBy('id', 'desc')->findAll($limit, $offset);
		$countQuery = $this->PembinaMutuJabatanModel->selectCount('id')->where($where)->find();
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

		$resp = $this->PembinaMutuJabatanModel->find($id);

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}

	public function create($pembina_mutu_id = 0)
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$checkPm = $this->db->query('SELECT * FROM tbl_pembina_mutu WHERE id = ?', $pembina_mutu_id)->getRow();

		if (!isset($checkPm->id)) {
			return ResponseNotFound();
		}

		$this->validation->setRules([
			'jabatan' 			=> 'required|min_length[5]',
			'unit_kerja' 		=> 'required|min_length[5]',
			'instansi' 			=> 'required|in_list[KKP,DINAS]',
			'masih_menjabat' 	=> 'required|in_list[YA,TIDAK]',
			'tahun_mulai' 		=> 'required|exact_length[4]|numeric',
			'tahun_selesai' 	=> 'required|exact_length[4]|numeric',
		], [
			'jabatan' => [
				'required'		=> 'wajib diisi',
				'min_length' 	=> 'minimal 2 karakter'
			],
			'unit_kerja' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 5 karakter'
			],
			'instansi' => [
				'required' 		=> 'wajib diisi',
				'in_list' 		=> 'tidak terdaftar'
			],
			'masih_menjabat' => [
				'required' 		=> 'wajib diisi',
				'in_list'		=> 'tidak terdaftar'
			],
			'tahun_mulai' => [
				'required' 		=> 'wajib diisi',
				'exact_length' 	=> 'hanya 4 karakter',
				'numeric'		=> 'isi dengan format angka tahun'
			],
			'tahun_selesai' => [
				'required' 		=> 'wajib diisi',
				'exact_length' 	=> 'hanya 4 karakter',
				'numeric'		=> 'isi dengan format angka tahun'
			]
		]);

		$reqArray = (array) $req->getJSON();

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$insert = array_merge(
			$reqArray,
			array(
				'pembina_mutu_id' => (int)$pembina_mutu_id
			)
		);

		$resp = $this->PembinaMutuJabatanModel->save($insert);

		return ResponseCreated(array( 'message' => 'riwayat pendidikan created' ));
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
			$foundRecord = $this->PembinaMutuJabatanModel->where('username', $reqArray['username'])->selectCount('id')->find($id);

			if (count($foundRecord) > 0 && (int)$foundRecord[0]['id'] > 0) {
				return ResponseConflict(array('message' => 'username already registered'));
			}
		}

		$reqArray['id'] = $id;

		$resp = $this->PembinaMutuJabatanModel->save($reqArray);

		return ResponseOK(array( 'message' => 'user updated' ));
	}

	public function delete($id = 0) {
		if ($id < 1) {
			return ResponseNotFound();
		}

		$deleted = $this->PembinaMutuJabatanModel->delete($id);

		return ResponseOK(array( 'message' => 'riwayat pendidikan deleted' ));
	}
}
