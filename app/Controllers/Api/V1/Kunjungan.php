<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\KunjunganModel;
use App\Models\UpiModel;
use App\Models\PembinaMutuJabatanModel;

class Kunjungan extends BaseController
{
	protected $KunjunganModel;
	protected $validation;
	protected $db;
	protected $kunjunganField = array(
		'validation' 			=> array(
			'upi_id'			=> 'required|numeric',
			'tanggal_kunjungan'	=> 'required|valid_date[Y-m-d]',
			'kegiatan'			=> 'required'
		),
		'message'				=> array(
			'upi_id'			=> [
				'required'		=> 'wajib diisi',
				'numeric'		=> 'nilai id tidak valid'
			],
			'tanggal_kunjungan'	=> [
				'required'		=> 'wajib diisi',
				'valid_date'	=> 'format tanggal tidak sesuai'
			],
			'kegiatan'			=> [
				'required'		=> 'wajib diisi'
			]
		)
	);

	function __construct()
	{
		$this->KunjunganModel = new KunjunganModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();
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

		// filter injection here
		$byUpiId = isset($q['upiId']) && filter_var($q['upiId'], FILTER_VALIDATE_INT) ? (int)$q['upiId'] : 0;
		$byPembinaMutuId = isset($q['pembinaMutuId']) && filter_var($q['pembinaMutuId'], FILTER_VALIDATE_INT) ? (int)$q['pembinaMutuId'] : 0;
		$getDetailPembinaMutu = isset($q['getDetailPembinaMutu']) && filter_var($q['getDetailPembinaMutu'], FILTER_VALIDATE_BOOLEAN) ? true : false;
		$getDetailUpi = isset($q['getDetailUpi']) && filter_var($q['getDetailUpi'], FILTER_VALIDATE_BOOLEAN) ? true : false;

		$whereQuery = array();

		if ($byUpiId > 0) {
			$whereQuery = array_merge($whereQuery, ['upi_id' => $byUpiId]);
		}

		if ($byPembinaMutuId > 0) {
			$whereQuery = array_merge($whereQuery, ['pembina_mutu_id' => $byPembinaMutuId]);
		}

		$selectQuery = 'tbl_kunjungan.*';
		$joinTables = array();
		$joinCondition = array();

		$selectQuery .= ', tbl_pembina_mutu.nama_lengkap as nama_pembina_mutu';
		array_push($joinTables, 'tbl_pembina_mutu');
		array_push($joinCondition, 'tbl_pembina_mutu.id = tbl_kunjungan.pembina_mutu_id');

		$selectQuery .= ', tbl_upi.nama_perusahaan as nama_upi';
		array_push($joinTables, 'tbl_upi');
		array_push($joinCondition, 'tbl_upi.id = tbl_kunjungan.upi_id');

		$selectQuery .= ', tbl_location.name as nama_provinsi';
		array_push($joinTables, 'tbl_location');
		array_push($joinCondition, 'tbl_upi.provinsi = tbl_location.id');

		$resp = $this->KunjunganModel->select($selectQuery)
			->join($joinTables[0], $joinCondition[0])
			->join($joinTables[1], $joinCondition[1])
			->join($joinTables[2], $joinCondition[2])
			->where($whereQuery)->orderBy('tbl_kunjungan.id', 'desc')->findAll($limit, $offset);

		$countQuery = $this->KunjunganModel
			->join($joinTables[0], $joinCondition[0])
			->join($joinTables[1], $joinCondition[1])
			->join($joinTables[2], $joinCondition[2])
			->where($whereQuery)->selectCount('tbl_kunjungan.id')->find();
		$count = (int)$countQuery[0]['id'];

		$pageAvailable = ceil((int)$count/(int)$limit);

		$current = $page;
		$next = ($pageAvailable <= $current) ? 0 : $page + 1;
		$prev = ($page == 1) ? 0 : $page - 1;

		$lists = [];

		if (count($resp) > 0) {
			if ($getDetailPembinaMutu)
			{
				$pmjm = new PembinaMutuJabatanModel();

				foreach($resp as $key => $value)
				{
					$getPmjm = $pmjm->where(array(
						'pembina_mutu_id' 	=> $value['pembina_mutu_id'],
						'masih_menjabat'	=> 'YA'
					))->orderBy('id', 'desc')->first();

					if ($getPmjm != null) {
						$resp[$key]['unit_kerja_terakhir'] = $getPmjm['unit_kerja'];
					} else {
						$resp[$key]['unit_kerja_terakhir'] = 'Tidak ada jabatan aktif';
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

		$resp = $this->KunjunganModel->find($id);

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

		$this->validation->setRules(
			$this->kunjunganField['validation'],
			$this->kunjunganField['message'],
		);

		$reqArray = (array) $req->getJSON();

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$UpiModel = new UpiModel();
		$upi = $UpiModel->find($reqArray['upi_id']);

		if ($upi == NULL) {
			return ResponseNotFound();
		}

		$pembinaMutuId = 10;

		$insert = array_merge(
			$reqArray,
			array(
				'pembina_mutu_id'	=> $pembinaMutuId
			)
		);

		$this->KunjunganModel->save($insert);

		return ResponseCreated(array( 'message' => 'kunjungan created' ));
	}

	public function update($id = 0)
	{
		if ($id < 1) {
			return ResponseNotFound();
		}

		$found = $this->KunjunganModel->find($id);

		if ($found == NULL) {
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
			if ($this->kunjunganField['validation'][$key] !== null && $this->kunjunganField['message'][$key] !== null) {
				$validationSetRules[$key] = $this->kunjunganField['validation'][$key];
				$validationSetMessages[$key] = $this->kunjunganField['message'][$key];
			}
		}

		$this->validation->setRules($validationSetRules, $validationSetMessages);

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$reqArray['id'] = $id;

		$resp = $this->KunjunganModel->save($reqArray);

		return ResponseOK(array( 'message' => 'kunjungan updated' ));
	}

	public function delete($id = 0) {
		if ($id < 1) {
			return ResponseNotFound();
		}

		$deleted = $this->KunjunganModel->delete($id);

		return ResponseOK(array( 'message' => 'kunjungan deleted' ));
	}
}
