<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\PembinaMutuPelatihanModel;

class PembinaMutuPelatihan extends BaseController
{
	protected $db;
	protected $PembinaMutuPelatihanModel;
	protected $validation;
	protected $vRules = array(
		'nama_pelatihan' 	=> 'required|min_length[2]',
		'penyelenggara' 	=> 'required|min_length[2]',
		'tahun_pelaksanaan'	=> 'required|exact_length[4]|numeric',
	);
	protected $vRulesMessages = array(
		'nama_pelatihan' => [
			'required' 		=> 'wajib diisi',
			'min_length' 	=> 'minimal 2 karakter'
		],
		'penyelenggara' => [
			'required' 		=> 'wajib diisi',
			'min_length' 	=> 'minimal 2 karakter'
		],
		'tahun_pelaksanaan' => [
			'required' 		=> 'wajib diisi',
			'exact_length' 	=> 'hanya 4 karakter',
			'numeric'		=> 'isi dengan format angka tahun'
		]
	);

	function __construct()
	{
		$this->PembinaMutuPelatihanModel = new PembinaMutuPelatihanModel();
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

		$resp = $this->PembinaMutuPelatihanModel->where($where)->orderBy('id', 'desc')->findAll($limit, $offset);
		$countQuery = $this->PembinaMutuPelatihanModel->selectCount('id')->where($where)->find();
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

		$resp = $this->PembinaMutuPelatihanModel->find($id);

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

		$this->validation->setRules($this->vRules, $this->vRulesMessages);

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

		$resp = $this->PembinaMutuPelatihanModel->save($insert);

		return ResponseCreated(array( 'message' => 'riwayat pelatihan created' ));
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

		$reqArray = (array) $req->getJSON();

		$validationSetRules = array();
		$validationSetMessages = array();

		foreach ($reqArray as $key => $val) {
			if ($this->vRules[$key] !== null && $this->vRulesMessages[$key] !== null) {
				$validationSetRules[$key] = $this->vRules[$key];
				$validationSetMessages[$key] = $this->vRulesMessages[$key];
			}
		}

		$this->validation->setRules($validationSetRules, $validationSetMessages);

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$reqArray['id'] = $id;

		$resp = $this->PembinaMutuPelatihanModel->save($reqArray);

		return ResponseOK(array( 'message' => 'riwayat pelatihan updated' ));
	}

	public function delete($id = 0) {
		if ($id < 1) {
			return ResponseNotFound();
		}

		$deleted = $this->PembinaMutuPelatihanModel->delete($id);

		return ResponseOK(array( 'message' => 'riwayat pelatihan deleted' ));
	}
}
