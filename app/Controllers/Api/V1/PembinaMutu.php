<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\PembinaMutuModel;
use App\Models\PembinaMutuJabatanModel;
use App\Models\PembinaMutuKeahlianModel;

class PembinaMutu extends BaseController
{
	protected $PembinaMutuModel;
	protected $PembinaMutuKeahlianModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->PembinaMutuModel = new PembinaMutuModel();
		$this->PembinaMutuKeahlianModel = new PembinaMutuKeahlianModel();
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

		$getInstansi = isset($q['getInstansi']) && filter_var($q['getInstansi'], FILTER_VALIDATE_BOOLEAN) ? true : false;
		$keyword = isset($q['keyword']) ? $q['keyword'] : '';

		$page = (isset($q['page'])) ? $q['page'] : 1;
		$limit = (isset($q['limit'])) ? $q['limit'] : 1;
		$offset = ($page - 1) * $limit;

		$joinQuery = 'tbl_user.id = tbl_pembina_mutu.user_id AND tbl_user.deleted_at IS NULL';


		$this->PembinaMutuModel->select('tbl_pembina_mutu.*')->join('tbl_user', $joinQuery);

		if ($keyword != '') {
			$cleanKey = "'%" . $this->db->escapeLikeString($keyword) . "%' ESCAPE '!'";
			$this->PembinaMutuModel->where('nama_lengkap LIKE' . $cleanKey);
		}

		$resp = $this->PembinaMutuModel->orderBy('tbl_pembina_mutu.id', 'desc')->findAll($limit, $offset);
		$countQuery = $this->PembinaMutuModel->selectCount('tbl_pembina_mutu.id')->find();
		$count = (int)$countQuery[0]['id'];

		$pageAvailable = ceil((int)$count/(int)$limit);

		$current = $page;
		$next = ($pageAvailable <= $current) ? 0 : $page + 1;
		$prev = ($page == 1) ? 0 : $page - 1;

		$lists = [];

		if (count($resp) > 0) {
			if ($getInstansi)
			{
				$pmjm = new PembinaMutuJabatanModel();

				foreach($resp as $key => $value)
				{
					$getPmjm = $pmjm->where(array(
						'pembina_mutu_id' 	=> $value['id'],
						'masih_menjabat'	=> 'YA'
					))->orderBy('id', 'desc')->first();

					if ($getPmjm != null) {
						$resp[$key]['instansi'] = $getPmjm['instansi'];
					} else {
						$resp[$key]['instansi'] = 'Tidak ada instansi';
					}
				}
			}

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

		$resp = $this->PembinaMutuModel->find($id);
		$resp['keahlian'] = $this->PembinaMutuKeahlianModel
			->select('tbl_badge.category, tbl_badge.name, tbl_badge.code, tbl_pembina_mutu_keahlian.id, tbl_pembina_mutu_keahlian.badge_id')
			->join('tbl_badge', 'tbl_badge.id = tbl_pembina_mutu_keahlian.badge_id')
			->where('pembina_mutu_id', $id)->findAll();

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}

	public function GetByUser($user_id = 0)
	{
		$req = $this->request;

		if (0 === $user_id) {
			return ResponseNotFound();
		}

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$resp = $this->PembinaMutuModel->where('user_id', $user_id)->find();

		if (count($resp) < 1) {
			return ResponseNotFound();
		}

		$transformed = array(
			'data' => $resp[0]
		);

		return ResponseOK($transformed);
	}

	public function create($user_id = 0)
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$checkUser = $this->db->query('SELECT * FROM tbl_user WHERE id = ?', $user_id)->getRow();

		if (!isset($checkUser->id)) {
			return ResponseNotFound();
		}

		$this->validation->setRules([
			'nama_lengkap' 	=> 'required|min_length[3]',
			'nip' 			=> 'required|min_length[11]',
			'foto_profil' 	=> 'required|min_length[3]|valid_url',
			'no_hp'			=> 'required|min_length[10]'
		], [
			'nama_lengkap' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 3 karakter'
			],
			'nip' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 11 karakter'
			],
			'foto_profil' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 3 karakter'
			],
			'no_hp' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 10 karakter'
			]
		]);

		$reqArray = (array) $req->getJSON();

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$foundRecord = $this->PembinaMutuModel->where('user_id', $user_id)->selectCount('id')->find();

		if (count($foundRecord) > 0 && (int)$foundRecord[0]['id'] > 0) {
			return ResponseConflict(array('message' => 'data already registered'));
		}

		$insert = array_merge(
			$reqArray,
			array(
				'user_id' => (int)$user_id
			)
		);


		$resp = $this->PembinaMutuModel->save($insert);

		return ResponseCreated(array( 'message' => 'pembina mutu created' ));
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

		$vRules = array(
			'nama_lengkap' 	=> 'required|min_length[3]',
			'nip' 			=> 'required|min_length[11]',
			'no_hp'			=> 'required|min_length[10]',
			'user_id'		=> 'required'
		);
		$vMessages = array(
			'nama_lengkap' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 3 karakter'
			],
			'nip' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 11 karakter'
			],
			'no_hp' => [
				'required' 		=> 'wajib diisi',
				'min_length' 	=> 'minimal 10 karakter'
			],
			'user_id' => [
				'required' 		=> 'wajib diisi',
			]
		);

		$reqArray = (array)$req->getJSON();

		$validationSetRules = array();
		$validationSetMessages = array();

		foreach ($reqArray as $key => $val) {
			if (isset($vRules[$key]) && isset($vMessages[$key])) {
				$validationSetRules[$key] = $vRules[$key];
				$validationSetMessages[$key] = $vMessages[$key];
			}
		}

		$this->validation->setRules($validationSetRules, $validationSetMessages);

		$reqArray['id'] = $id;
		$keahlian = $reqArray['keahlian'];
		$reqArray['keahlian'] = '';

		$resp = $this->PembinaMutuModel->save($reqArray);

		if (count($keahlian) > 0)
		{
			$this->_deleteAllPembinaMutuKeahlian($id);
			foreach($keahlian as $v)
			{
				if ((int)$v > 0)
				{
					$this->_upsertPembinaMutuKeahlian($id, $v);
				}
			}
		}

		return ResponseOK(array( 'message' => 'user updated' ));
	}

	public function _deleteAllPembinaMutuKeahlian($pmId)
	{
		return $this->PembinaMutuKeahlianModel->where([ 'pembina_mutu_id' => $pmId ])->delete();
	}

	public function _upsertPembinaMutuKeahlian($pmId, $badgeId)
	{
		$badge = $this->PembinaMutuKeahlianModel->where(['pembina_mutu_id' => $pmId, 'badge_id' => $badgeId ])->get()->getRow();
		$payload = array(
			'pembina_mutu_id' 	=> $pmId,
			'badge_id'			=> $badgeId,
			'deleted_at'		=> NULL
		);

		if (null!=$badge)
		{
			$payload['id'] = $badge->id;
		}

		return $this->PembinaMutuKeahlianModel->save($payload);
	}

	public function delete($id = 0) {
		if ($id < 1) {
			return ResponseNotFound();
		}

		$deleted = $this->PembinaMutuModel->delete($id);

		return ResponseOK(array( 'message' => 'user deleted' ));
	}
}
