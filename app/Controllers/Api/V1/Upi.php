<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UpiModel;
use App\Models\LocationModel;
use App\Models\CountryModel;
use App\Models\UpiProduksiModel;
use App\Models\ProdukModel;
use App\Models\ProduksiProdukModel;
use App\Models\ProduksiPemasaranModel;
use App\Models\UpiSarprasModel;
use App\Models\UpiTenagaKerjaModel;
use App\Models\PerubahanUpiModel;

class Upi extends BaseController
{
	protected $UpiModel;
	protected $validation;
	protected $db;
	protected $sertifikasiPerusahaan = array('SKP', 'HACCP', 'HALAL', 'BRC', 'ISO 9001');
	protected $upiDataUmum = array(
		'validation' => [
			'nama_perusahaan' 			=> 'required|min_length[5]',
			'alamat_pabrik' 			=> 'required|min_length[5]',
			'koordinat_lokasi' 			=> 'if_exist|min_length[5]',
			'no_kusuka' 				=> 'required|min_length[5]',
			'npwp'						=> 'required|numeric|min_length[15]|max_length[15]',
			'nib'						=> 'required|min_length[5]',
			'sertifikasi_perusahaan'	=> 'required|min_length[3]',
			'sumber_permodalan' 		=> 'required|min_length[3]|in_list[PMDN,PMA]',
			'deskripsi' 				=> 'if_exist|min_length[5]',
			'website' 					=> 'if_exist|min_length[5]',
			'nama_pemilik' 				=> 'required|min_length[5]',
			'nama_kontak_upi' 			=> 'required|min_length[5]',
			'foto_pabrik' 				=> 'if_exist|valid_url',
			'kecamatan'					=> 'required|numeric|greater_than[0]',
			'kab_kota' 					=> 'required|numeric|greater_than[0]',
			'provinsi' 					=> 'required|numeric|greater_than[0]'
		],
		'message' => [
			'nama_perusahaan'			=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'minimum 5 karakter'
			],
			'alamat_pabrik'		=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'minimum 5 karakter'
			],
			'koordinat_lokasi'	=> [
				'min_length'	=> 'minimum 5 karakter'
			],
			'no_kusuka'			=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'minimum 5 karakter'
			],
			'npwp'				=> [
				'required' 		=> 'wajib diisi',
				'numeric'		=> 'hanya angka NPWP saja',
				'min_length'	=> '15 karakter',
				'max_length'	=> '15 karakter'
			],
			'nib'				=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'minimum 5 karakter'
			],
			'sertifikasi_perusahaan'=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'minimum 3 karakter'
			],
			'sumber_permodalan'=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'wajib diisi',
				'in_list'		=> 'input tidak valid'
			],
			'deskripsi'=> [
				'min_length'	=> 'minimum 5 karakter'
			],
			'website'=> [
				'min_length'	=> 'minimum 5 karakter'
			],
			'nama_pemilik'=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'minimum 5 karakter'
			],
			'nama_kontak_upi'=> [
				'required' 		=> 'wajib diisi',
				'min_length'	=> 'minimum 5 karakter'
			],
			'foto_pabrik'=> [
				'required' 		=> 'wajib diisi',
				'valid_url'		=> 'url gambar tidak valid'
			],
			'kecamatan'=> [
				'required' 		=> 'wajib diisi',
				'numeric'		=> 'hanya angka saja',
				'greater_than'	=> 'tidak valid'
			],
			'kab_kota'=> [
				'required' 		=> 'wajib diisi',
				'numeric'		=> 'hanya angka saja',
				'greater_than'	=> 'tidak valid'
			],
			'provinsi'=> [
				'required' 		=> 'wajib diisi',
				'numeric'		=> 'hanya angka saja',
				'greater_than'	=> 'tidak valid'
			],
		]
	);
	protected $upiProduksi = array(
		'validation' => [
			'merk_dagang'				=> 'required|min_length[5]',
			'produk_utama'				=> 'required|greater_than[0]|numeric',
			'kapasitas_produksi_hari'	=> 'required|greater_than[0]|numeric',
			'rata_hari_produksi_bulan'	=> 'required|greater_than[0]|numeric',
			'rata_bulan_produksi_tahun'	=> 'required|greater_than[0]|numeric'
		],
		'message' => [
			'merk_dagang'				=> [
				'required'				=> 'wajib diisi',
				'min_length'			=> 'minimal 5 karakter'
			],
			'produk_utama'				=> [
				'required'				=> 'wajib diisi',
				'greater_than'			=> 'nilai tidak valid',
				'numeric'				=> 'format tidak valid'
			],
			'kapasitas_produksi_hari'	=> [
				'required'				=> 'wajib diisi',
				'greater_than'			=> 'nilai tidak valid',
				'numeric'				=> 'format tidak valid'
			],
			'rata_hari_produksi_bulan'	=> [
				'required'				=> 'wajib diisi',
				'greater_than'			=> 'nilai tidak valid',
				'numeric'				=> 'format tidak valid'
			],
			'rata_bulan_produksi_tahun'	=> [
				'required'				=> 'wajib diisi',
				'greater_than'			=> 'nilai tidak valid',
				'numeric'				=> 'format tidak valid'
			]
		]
	);
	protected $upiProduksiProduk = array(
		'validation' => [
			'produk_dihasilkan'		=> 'required',
			'produk_dihasilkan.*'	=> 'numeric|greater_than[0]'
		],
		'message' => [
			'produk_dihasilkan'	=> [
				'required'		=> 'wajib diisi',
			],
			'produk_dihasilkan.*'	=> [
				'numeric'		=> 'nilai tidak valid',
				'greater_than'	=> 'data tidak valid'
			]
		]
	);
	protected $upiProduksiPemasaran = array(
		'validation' => [
			'pemasaran_domestik'	=> 'required_without[pemasaran_ekspor]',
			'pemasaran_domestik.*'	=> 'numeric|greater_than[0]',
			'pemasaran_ekspor'		=> 'required_without[pemasaran_domestik]',
			'pemasaran_ekspor.*'	=> 'numeric|greater_than[0]'
		],
		'message' => [
			'pemasaran_domestik'	=> [
				'required_without' 	=> 'wajib isi minimal salah satu pemasaran: ekspor atau domestik',
			],
			'pemasaran_domestik.*'	=> [
				'numeric'			=> 'nilai tidak valid',
				'greater_than'		=> 'data tidak valid'
			],
			'pemasaran_ekspor'		=> [
				'required_without' 	=> 'wajib isi minimal salah satu pemasaran: ekspor atau domestik',
			],
			'pemasaran_ekspor.*'	=> [
				'numeric'			=> 'nilai tidak valid',
				'greater_than'		=> 'data tidak valid'
			]
		]
	);
	protected $upiSarpras = array(
		'validation' => [
			'*.sarpras_id'		=> 'numeric',
			'*.nilai_unit'		=> 'numeric',
			'*.nilai_kapasitas'	=> 'numeric',
			'*.satuan'			=> 'in_list[ton,kg]'
		],
		'message' => [
			'sarpras_id'		=> [
				'numeric'		=> 'nilai dalam bentuk angka'
			],
			'nilai_unit'		=> [
				'numeric'		=> 'nilai dalam bentuk angka'
			],
			'nilai_kapasitas'	=> [
				'numeric'		=> 'nilai dalam bentuk angka'
			],
			'satuan'			=> [
				'in_list'		=> 'hanya menerima ton atau kilogram'
			]
		]
	);
	protected $upiTenagaKerja = array(
		'validation' => [
			'karyawan_tetap_l' 		=> 'numeric',
			'karyawan_tetap_p'		=> 'numeric',
			'karyawan_harian_l'		=> 'numeric',
			'karyawan_harian_p'		=> 'numeric',
			'hari_kerja_bulan'		=> 'numeric|greater_than[0]|less_than[32]',
			'shift_kerja_hari'		=> 'numeric|greater_than[0]|less_than[5]'
		],
		'message' => [
			'karyawan_tetap_l'		=> [
				'numeric'	=> 'nilai dalam bentuk angka'
			],
			'karyawan_tetap_p'		=> [
				'numeric'	=> 'nilai dalam bentuk angka'
			],
			'karyawan_harian_l'		=> [
				'numeric'	=> 'nilai dalam bentuk angka'
			],
			'karyawan_harian_p'		=> [
				'numeric'	=> 'nilai dalam bentuk angka'
			],
			'hari_kerja_bulan'		=> [
				'numeric'		=> 'nilai dalam bentuk angka',
				'greater_than'	=> 'nilai minimal 1',
				'less_than'		=> 'nilai maximal 31'
			],
			'shift_kerja_hari'		=> [
				'numeric'	=> 'nilai dalam bentuk angka',
				'greater_than'	=> 'nilai minimal 1',
				'less_than'		=> 'nilai maximal 4'
			]
		]
	);
	protected $upiCompleteStructure = array();

	function __construct()
	{
		$this->UpiModel = new UpiModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();
		$this->upiCompleteStructure = array(
			'data_umum' 		=> array(),
			'data_produksi' 	=> array(
				'produk_dihasilkan'		=> array(),
				'pemasaran_domestik'	=> array(),
				'pemasaran_ekspor'		=> array(),
				'foto_produk'			=> array()
			),
			'data_tenaga_kerja'	=> array(),
			'data_sarpras'		=> array(),
		);
	}

	public function GetAll()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();

		$extraQuery = array();

		$getLocationName = (isset($q['getLocationName']) && ((bool)$q['getLocationName'])) ? true : false;
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

			foreach($resp as $key => $value) {
				if($getLocationName) {
					$extraQuery['getLocationName'] = true;

					$locationModel = new LocationModel();
					$province = $locationModel->GetProvinceById($value['provinsi']);
					$regency = $locationModel->GetRegencyById($value['kab_kota']);
					$district = $locationModel->GetDistrictById($value['kecamatan']);
					$subDistrict = $locationModel->GetSubDistrictById($value['kelurahan_desa']);

					$resp[$key]['location_province_name'] = $province->name;
					$resp[$key]['location_regency_name'] = $regency->name;
					$resp[$key]['location_district_name'] = $district->name;
					$resp[$key]['location_sub_district_name'] = $subDistrict->name;
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

		$transformed['queries'] = array_merge($transformed['queries'], $extraQuery);

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

		$q = $req->getGet();

		$getLocation = (isset($q['getLocation']) && filter_var($q['getLocation'], FILTER_VALIDATE_BOOLEAN)) ? true : false;

		$resp = $this->UpiModel->find($id);

		if (null !== $resp) {
			if ($getLocation) {
				$locationModel = new LocationModel();

				$resp['location'] = array(
					'province' 		=> $locationModel->GetProvinceById($resp['provinsi']),
					'regency' 		=> $locationModel->GetRegencyById($resp['kab_kota']),
					'district' 		=> $locationModel->GetDistrictById($resp['kecamatan']),
					'sub_district' 	=> $locationModel->GetSubDistrictById($resp['kelurahan_desa'])
				);
			}
		}

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}

	public function GetCompleteById($id = 0)
	{
		$resp = $this->UpiModel->find($id);

		$transformed = array(
			'data' => $this->_mapCompleteUpi($resp)
		);

		return ResponseOK($transformed);
	}

	public function _mapCompleteUpi($data)
	{
		$transformed = $this->upiCompleteStructure;
		$transformed['data_umum'] = $this->_cleanField($data);
		$upiId = (int)$data['id'];

		if ($upiId>0) {
			$upiProduksiModel = new UpiProduksiModel();
			$produksiProdukModel = new ProduksiProdukModel();
			$produksiPemasaranModel = new ProduksiPemasaranModel();
			$upiSarprasModel = new UpiSarprasModel();
			$upiTenagaKerjaModel = new UpiTenagaKerjaModel();

			$upiProduksi = $upiProduksiModel->where('upi_id', $upiId)->first();

			$upiProduksiId = (int)$upiProduksi['id'];

			$upiProduk = $produksiProdukModel->GetProdukByProduksiId($upiProduksiId);
			$upiPemasaranEkspor = $produksiPemasaranModel->GetPemasaranByProduksiIdAndType($upiProduksiId, 'ekspor');
			$upiPemasaranDomestik = $produksiPemasaranModel->GetPemasaranByProduksiIdAndType($upiProduksiId, 'domestik');

			$transformed['data_produksi'] = array_merge(
				$transformed['data_produksi'],
				$this->_cleanField($upiProduksi)
			);
			foreach($upiProduk as $k => $v) {
				$upiProduk[$k] = $this->_cleanField((array)$v);
			}
			foreach($upiPemasaranEkspor as $k => $v) {
				$upiPemasaranEkspor[$k] = $this->_cleanField((array)$v);
			}
			foreach($upiPemasaranDomestik as $k => $v) {
				$upiPemasaranDomestik[$k] = $this->_cleanField((array)$v);
			}

			$upiSarprasModel->select('tbl_upi_sarpras.*, tbl_sarpras.*, tbl_sarpras.id as upi_sarpras_id');
			$joinCondition = 'tbl_sarpras.id = tbl_upi_sarpras.sarpras_id AND tbl_upi_sarpras.upi_id =' . (int)$upiId;
			$upiSarprasModel->join('tbl_sarpras', $joinCondition, 'right');
			$upiSarprasModel->orderBy('tbl_sarpras.id', 'asc');
			$upiSarpras = $upiSarprasModel->findAll();

			foreach($upiSarpras as $k => $v) {
				$upiSarpras[$k] = $this->_cleanField((array)$v);
				$upiSarpras[$k]['satuan'] = 'kg';
			}

			$upiTenagaKerja = $upiTenagaKerjaModel->where('upi_id', $upiId)->first();

			$transformed['data_produksi']['produk_dihasilkan'] 	= $upiProduk;
			$transformed['data_produksi']['pemasaran_ekspor'] 	= $upiPemasaranEkspor;
			$transformed['data_produksi']['pemasaran_domestik'] = $upiPemasaranDomestik;
			$transformed['data_sarpras'] = $upiSarpras;
			$transformed['data_tenaga_kerja'] = $this->_cleanField($upiTenagaKerja);
		}

		return $transformed;
	}

	public function _cleanField($data)
	{
		foreach(
			array(
				'is_main',
				'created_at',
				'updated_at',
				'deleted_at',
			)
		as $val)
		{
			unset($data[$val]);
		}

		return $data;
	}

	public function _validateUpiCompleteInput($reqArray = array())
	{
		$this->validation->setRules(
			$this->upiDataUmum['validation'],
			$this->upiDataUmum['message']
		);

		if(!$this->validation->run($reqArray['data_umum'])) {
			return $this->validation->getErrors();
		}

		// validate province, regency, district and sub district
		$locationModel = new LocationModel();
		$getLocation = $locationModel->GetCompleteJoinFromSubDistrict($reqArray['data_umum']['kelurahan_desa']);

		if (null===$getLocation || $getLocation->subdistrict != $reqArray['data_umum']['kelurahan_desa']) {
			return array('message' => 'data kelurahan atau desa tidak valid');
		}

		if ($getLocation->district != $reqArray['data_umum']['kecamatan']) {
			return array('message' => 'data kecamatan tidak sesuai');
		}

		if ($getLocation->regency != $reqArray['data_umum']['kab_kota']) {
			return array('message' => 'data kota atau kabupaten tidak sesuai');
		}

		if ($getLocation->province != $reqArray['data_umum']['provinsi']) {
			return array('message' => 'data provinsi tidak sesuai');
		}

		$this->validation->setRules(
			array_merge(
				$this->upiProduksi['validation'],
				$this->upiProduksiProduk['validation'],
				$this->upiProduksiPemasaran['validation']
			),
			array_merge(
				$this->upiProduksi['message'],
				$this->upiProduksiProduk['message'],
				$this->upiProduksiPemasaran['message']
			)
		);

		if(!$this->validation->run($reqArray['data_produksi'])) {
			return $this->validation->getErrors();
		}

		// validasi produk utama
		if(!in_array($reqArray['data_produksi']['produk_utama'], array_unique($reqArray['data_produksi']['produk_dihasilkan'])))
		{
			return array('message' => 'produk utama tidak terdaftar pada produk dihasilkan');
		}

		$this->validation->setRules(
			$this->upiTenagaKerja['validation'],
			$this->upiTenagaKerja['message']
		);

		if(!$this->validation->run($reqArray['data_tenaga_kerja'])) {
			return $this->validation->getErrors();
		}

		$this->validation->setRules(
			$this->upiSarpras['validation'],
			$this->upiSarpras['message']
		);

		if(!$this->validation->run($reqArray['data_sarpras'])) {
			return $this->validation->getErrors();
		}

		return true;
	}

	public function _saveUpiComplete($reqArray = array())
	{
		// filter produk dihasilkan hanya aktif di db saja
		$produkModel = new ProdukModel();
		$getProdukDihasilkan = $produkModel->GetProductByIds($reqArray['data_produksi']['produk_dihasilkan']);

		$validProdukDihasilkan = array();

		foreach($getProdukDihasilkan as $v) {
			array_push($validProdukDihasilkan, (int)$v['id']);
		}

		// validasi produk utama dalam produk dihasilkan yg aktif saja
		if(!in_array($reqArray['data_produksi']['produk_utama'], $validProdukDihasilkan))
		{
			return array('message' => 'produk utama tidak terdaftar pada valid produk dihasilkan');
		}

		// validasi pemasaran domestik atau mancanegara valid
		$validEksporData = array();
		$validDomestikData = array();

		if (count($reqArray['data_produksi']['pemasaran_domestik']) > 0) {
			$locationModel = new LocationModel();
			$getRegency = $locationModel->GetRegencyByIds($reqArray['data_produksi']['pemasaran_domestik']);

			foreach($getRegency as $v) {
				array_push($validDomestikData, (int)$v['id']);
			}
		}

		if (count($reqArray['data_produksi']['pemasaran_ekspor']) > 0) {
			$countryModel = new CountryModel();
			$getCountry = $countryModel->GetByIds($reqArray['data_produksi']['pemasaran_ekspor']);

			foreach($getCountry as $v) {
				array_push($validEksporData, (int)$v['id']);
			}
		}

		$this->db->transBegin();
		$now = date("Y-m-d H:i:s");
		// insert data umum to tbl_upi
		$insertDataUpi = $reqArray['data_umum'];
		$insertDataUpi['created_at'] = $now;
		$insertDataUpi['updated_at'] = $now;
		$insertDataUpi['created_by'] = 1;
		// TODO: inject user id while create new upi data
		$this->db->table('tbl_upi')->insert($insertDataUpi);
		$upiId = $this->db->insertID();
		// insert upi produksi with upi_id
		$insertProduksi = $reqArray['data_produksi'];
		unset($insertProduksi['produk_dihasilkan']);
		unset($insertProduksi['pemasaran_domestik']);
		unset($insertProduksi['pemasaran_ekspor']);
		$insertProduksi['upi_id'] = $upiId;
		$insertProduksi['created_at'] = $now;
		$insertProduksi['updated_at'] = $now;
		$this->db->table('tbl_upi_produksi')->insert($insertProduksi);
		$upiProduksiId = $this->db->insertID();
		// insert sarpras with upi_id
		$insertSarpras = array();
		foreach($reqArray['data_sarpras'] as $v) {
			$nilaiKapasitas = $v['satuan'] == 'ton' ? $v['nilai_kapasitas'] * 1000 : $v['nilai_kapasitas'];
			array_push($insertSarpras, array(
				'upi_id'			=> $upiId,
				'sarpras_id'			=> $v['sarpras_id'],
				'nilai_unit'		=> $v['nilai_unit'],
				'nilai_kapasitas'	=> $nilaiKapasitas,
				'created_at'		=> $now,
				'updated_at'		=> $now
			));
		}
		$this->db->table('tbl_upi_sarpras')->insertBatch($insertSarpras);
		// insert tenaga kerja with upi_id
		$insertTenagaKerja = $reqArray['data_tenaga_kerja'];
		$insertTenagaKerja['upi_id'] = $upiId;
		$insertTenagaKerja['created_at'] = $now;
		$insertTenagaKerja['updated_at'] = $now;
		$this->db->table('tbl_upi_tenaga_kerja')->insert($insertTenagaKerja);
		// insert produk with upi_produksi_id
		$insertProduksiProduk = array();
		foreach($validProdukDihasilkan as $v) {
			array_push($insertProduksiProduk, array(
				'upi_produksi_id'	=> $upiProduksiId,
				'produk_id'			=> $v,
				'created_at'		=> $now,
				'updated_at'		=> $now
			));
		}
		$this->db->table('tbl_produksi_produk')->insertBatch($insertProduksiProduk);
		// insert pemasaran with upi_produksi_id
		$insertProduksiPemasaran = array();
		foreach($validEksporData as $v) {
			array_push($insertProduksiPemasaran, array(
				'upi_produksi_id'	=> $upiProduksiId,
				'target_pemasaran'	=> $v,
				'tipe_pemasaran'	=> 'ekspor',
				'created_at'		=> $now,
				'updated_at'		=> $now
			));
		}
		foreach($validDomestikData as $v) {
			array_push($insertProduksiPemasaran, array(
				'upi_produksi_id'	=> $upiProduksiId,
				'target_pemasaran'	=> $v,
				'tipe_pemasaran'	=> 'domestik',
				'created_at'		=> $now,
				'updated_at'		=> $now
			));
		}
		$this->db->table('tbl_produksi_pemasaran')->insertBatch($insertProduksiPemasaran);

		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();

			return array('message' => $this->db->transStatus());
		} else {
			$this->db->transCommit();
		}

		return true;
	}

	public function createComplete()
	{
		$req = $this->request;
		$reqArray = $req->getJSON(true);

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$validate = $this->_validateUpiCompleteInput($reqArray);

		if (is_array($validate)) {
			return ResponseError(400, array('message' => $validate));
		}

		$saved = $this->_saveUpiComplete($reqArray);

		if (!$saved) {
			return ResponseError(400, array('message' => 'Failed to save data!'));
		}

		return ResponseCreated(array( 'message' => 'UPI created' ));
	}

	public function _updateUpiComplete($upiId, $reqArray = array())
	{
		// Check UPI is valid
		$validUpi = $this->UpiModel->find($upiId);

		if (null == $validUpi) {
			return array('message' => 'upi tidak terdaftar');
		}

		$upiProduksiModel = new UpiProduksiModel();
		$getUpiProduksi = $upiProduksiModel->select('id')->where('upi_id', $upiId)->get()->getRowArray();

		if (null == $getUpiProduksi) {
			return array('message' => 'upi produksi tidak valid');
		}

		$upiProduksiId = (int)$getUpiProduksi['id'];

		// filter produk dihasilkan hanya aktif di db saja
		$produkModel = new ProdukModel();
		$getProdukDihasilkan = $produkModel->GetProductByIds($reqArray['data_produksi']['produk_dihasilkan']);

		$validProdukDihasilkan = array();

		foreach($getProdukDihasilkan as $v) {
			array_push($validProdukDihasilkan, (int)$v['id']);
		}

		// validasi produk utama dalam produk dihasilkan yg aktif saja
		if(!in_array($reqArray['data_produksi']['produk_utama'], $validProdukDihasilkan))
		{
			return array('message' => 'produk utama tidak terdaftar pada valid produk dihasilkan');
		}

		// validasi pemasaran domestik atau mancanegara valid
		$validEksporData = array();
		$validDomestikData = array();

		if (count($reqArray['data_produksi']['pemasaran_domestik']) > 0) {
			$locationModel = new LocationModel();
			$getRegency = $locationModel->GetRegencyByIds($reqArray['data_produksi']['pemasaran_domestik']);

			foreach($getRegency as $v) {
				array_push($validDomestikData, (int)$v['id']);
			}
		}

		if (count($reqArray['data_produksi']['pemasaran_ekspor']) > 0) {
			$countryModel = new CountryModel();
			$getCountry = $countryModel->GetByIds($reqArray['data_produksi']['pemasaran_ekspor']);

			foreach($getCountry as $v) {
				array_push($validEksporData, (int)$v['id']);
			}
		}

		$this->db->transBegin();
		$now = date("Y-m-d H:i:s");
		// update data umum to tbl_upi
		$insertDataUpi = $reqArray['data_umum'];
		$insertDataUpi['updated_at'] = $now;
		// TODO: inject user id while create new upi data
		$this->db->table('tbl_upi')->where('id', $upiId)->update($insertDataUpi);
		// update upi produksi with upi_id
		$insertProduksi = $reqArray['data_produksi'];
		unset($insertProduksi['produk_dihasilkan']);
		unset($insertProduksi['pemasaran_domestik']);
		unset($insertProduksi['pemasaran_ekspor']);
		$insertProduksi['upi_id'] = $upiId;
		$insertProduksi['updated_at'] = $now;
		$this->db->table('tbl_upi_produksi')->where('id', $upiProduksiId)->update($insertProduksi);
		// update tenaga kerja with upi_id
		$insertTenagaKerja = $reqArray['data_tenaga_kerja'];
		$insertTenagaKerja['upi_id'] = $upiId;
		$insertTenagaKerja['updated_at'] = $now;
		$this->db->table('tbl_upi_tenaga_kerja')->where('upi_id', $upiId)->update($insertTenagaKerja);
		// update sarpras with upi_id
		foreach($reqArray['data_sarpras'] as $v) {
			$nilaiKapasitas = $v['satuan'] == 'ton' ? $v['nilai_kapasitas'] * 1000 : $v['nilai_kapasitas'];

			if ($nilaiKapasitas > 0 && $v['nilai_unit'] > 0) {
				// find and update
				$getSarpras = $this->db->table('tbl_upi_sarpras')
					->select('id')
					->where(['upi_id' => $upiId, 'sarpras_id' => $v['sarpras_id']])
					->get()
					->getRowArray();

				if (null !== $getSarpras) {
					$upiSarprasId = $getSarpras['id'];
					$this->db->table('tbl_upi_sarpras')->where('id', $upiSarprasId)->update(
						array(
							'nilai_unit'		=> $v['nilai_unit'],
							'nilai_kapasitas'	=> $nilaiKapasitas,
							'updated_at'		=> $now
						)
					);
				}
			}

			if ($v['nilai_unit']==NULL || $v['nilai_kapasitas']==NULL) {
				$this->db->table('tbl_upi_sarpras')->insert(array(
					'upi_id'			=> $upiId,
					'sarpras_id'		=> $v['sarpras_id'],
					'nilai_unit'		=> 0,
					'nilai_kapasitas'	=> 0,
					'created_at'		=> $now,
					'updated_at'		=> $now
				));
			}
		}
		// update produk with upi_produksi_id
		$insertBatchProdukDihasilkan = array();
		$updateBatchProdukDihasilkan = array();
		$this->db->table('tbl_produksi_produk')
			->where('upi_produksi_id', $upiProduksiId)
			->update(
			array(
				'deleted_at' => $now
			)
		);
		foreach($validProdukDihasilkan as $v) {
			$getProdukDihasilkan = $this->db->table('tbl_produksi_produk')
				->select('id')
				->where(['upi_produksi_id' => $upiProduksiId, 'produk_id' => $v])
				->get()
				->getRowArray();

			if (null !== $getProdukDihasilkan) {
				array_push($updateBatchProdukDihasilkan, array(
					'id'				=> $getProdukDihasilkan['id'],
					'updated_at'		=> $now,
					'deleted_at'		=> 'NULL'
				));
			} else {
				array_push($insertBatchProdukDihasilkan, array(
					'upi_produksi_id'	=> $upiProduksiId,
					'produk_id'			=> $v,
					'created_at'		=> $now,
					'updated_at'		=> $now
				));
			}
		}
		if (count($insertBatchProdukDihasilkan)>0) {
			$this->db->table('tbl_produksi_produk')->insertBatch($insertBatchProdukDihasilkan);
		}
		if (count($updateBatchProdukDihasilkan)>0) {
			$this->db->table('tbl_produksi_produk')->updateBatch($updateBatchProdukDihasilkan, 'id');
		}
		// insert pemasaran with upi_produksi_id
		$insertBatchEksporProduksiPemasaran = array();
		$updateBatchEksporProduksiPemasaran = array();
		$this->db->table('tbl_produksi_pemasaran')
			->where('upi_produksi_id', $upiProduksiId)
			->update(
			array(
				'deleted_at' => $now
			)
		);
		foreach($validEksporData as $v) {
			$getPemasaran = $this->db->table('tbl_produksi_pemasaran')
				->select('id')
				->where(['upi_produksi_id' => $upiProduksiId, 'tipe_pemasaran' => 'ekspor', 'target_pemasaran'	=> $v])
				->get()
				->getRowArray();

			if (null !== $getPemasaran) {
				array_push($updateBatchEksporProduksiPemasaran, array(
					'id'			=> $getPemasaran['id'],
					'updated_at'	=> $now,
					'deleted_at'	=> 'NULL'
				));
			} else {
				array_push($insertBatchEksporProduksiPemasaran, array(
					'upi_produksi_id'	=> $upiProduksiId,
					'target_pemasaran'	=> $v,
					'tipe_pemasaran'	=> 'ekspor',
					'created_at'		=> $now,
					'updated_at'		=> $now
				));
			}
		}
		if (count($insertBatchEksporProduksiPemasaran)>0) {
			$this->db->table('tbl_produksi_pemasaran')->insertBatch($insertBatchEksporProduksiPemasaran);
		}
		if (count($updateBatchEksporProduksiPemasaran)>0) {
			$this->db->table('tbl_produksi_pemasaran')->updateBatch($updateBatchEksporProduksiPemasaran, 'id');
		}
		$insertBatchDomestikProduksiPemasaran = array();
		$updateBatchDomestikProduksiPemasaran = array();
		foreach($validDomestikData as $v) {
			$getPemasaran = $this->db->table('tbl_produksi_pemasaran')
				->select('id')
				->where(['upi_produksi_id' => $upiProduksiId, 'tipe_pemasaran' => 'domestik', 'target_pemasaran' => $v])
				->get()
				->getRowArray();

			if (null !== $getPemasaran) {
				array_push($updateBatchDomestikProduksiPemasaran, array(
					'id'			=> $getPemasaran['id'],
					'updated_at'	=> $now,
					'deleted_at'	=> 'NULL'
				));
			} else {
				array_push($insertBatchDomestikProduksiPemasaran, array(
					'upi_produksi_id'	=> $upiProduksiId,
					'target_pemasaran'	=> $v,
					'tipe_pemasaran'	=> 'domestik',
					'created_at'		=> $now,
					'updated_at'		=> $now
				));
			}
		}
		if (count($insertBatchDomestikProduksiPemasaran)>0) {
			$this->db->table('tbl_produksi_pemasaran')->insertBatch($insertBatchDomestikProduksiPemasaran);
		}
		if (count($updateBatchDomestikProduksiPemasaran)>0) {
			$this->db->table('tbl_produksi_pemasaran')->updateBatch($updateBatchDomestikProduksiPemasaran, 'id');
		}

		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();

			return array('message' => $this->db->transStatus());
		} else {
			$this->db->transCommit();
		}

		return true;
	}

	public function updateComplete($id = 0)
	{
		if (0 == $id) {
			return ResponseNotFound();
		}

		$req = $this->request;
		$reqArray = $req->getJSON(true);

		if ($req->getMethod(TRUE) !== 'PATCH') {
			return ResponseNotAllowed();
		}

		$validate = $this->_validateUpiCompleteInput($reqArray);

		if (is_array($validate)) {
			return ResponseError(400, array('message' => $validate));
		}

		$updated = $this->_updateUpiComplete($id, $reqArray);

		if (is_array($updated)) {
			return ResponseError(400, array('message' => 'Failed to save data: '.$updated['message']));
		}

		return ResponseCreated(array( 'message' => 'UPI updated' ));
	}

	public function _requestUpdatePerubahanUpi($data)
	{
		$perubahanUpiModel = new PerubahanUpiModel();

		return $perubahanUpiModel->save($data);
	}

	public function requestUpdate($id = 0)
	{
		if (0 == $id) {
			return ResponseNotFound();
		}

		$req = $this->request;
		$reqArray = $req->getJSON(true);

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$validate = $this->_validateUpiCompleteInput($reqArray);

		if (is_array($validate)) {
			return ResponseError(400, array('message' => $validate));
		}

		$pembinaMutuId = 1;

		$updated = $this->_requestUpdatePerubahanUpi(array(
			'upi_id' 			=> $id,
			'pembina_mutu_id'	=> $pembinaMutuId,
			'status'			=> 'requested',
			'data_perubahan'	=> json_encode($reqArray)
		));

		return ResponseCreated(array( 'message' => 'Request Perubahan Data UPI Created' ));
	}

	public function requestUpdatePerubahaUpi($id = 0, $action = 'rejected')
	{

	}

	public function search()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();

		$keyword = isset($q['keyword']) ? $q['keyword'] : '';
		$mapSelect = (isset($q['mapSelect']) && filter_var($q['mapSelect'], FILTER_VALIDATE_BOOLEAN)) ? true : false;
		$minSearch = 1;

		if (strlen($keyword) < $minSearch) {
			return ResponseBadRequest(array('message' => 'minimal '.$minSearch.' karakter'));
		}

		if (strlen($keyword) > 50) {
			return ResponseBadRequest(array('message' => 'keyword terlalu panjang'));
		}

		$cleanKey = "'%" . $this->db->escapeLikeString($keyword) . "%' ESCAPE '!'";
		$resp = $this->UpiModel->where('nama_perusahaan LIKE' . $cleanKey)->findAll(50, 0);
		$clean = array();

		foreach($resp as $key => $value) {
			$locationModel = new LocationModel();

			$province = $locationModel->GetProvinceById($value['provinsi']);
			$regency = $locationModel->GetRegencyById($value['kab_kota']);
			$district = $locationModel->GetDistrictById($value['kecamatan']);
			$subDistrict = $locationModel->GetSubDistrictById($value['kelurahan_desa']);

			$resp[$key]['location_province_name'] = $province->name;
			$resp[$key]['location_regency_name'] = $regency->name;
			$resp[$key]['location_district_name'] = $district->name;
			$resp[$key]['location_sub_district_name'] = $subDistrict->name;

			if ($mapSelect) {
				$clean[$key] = array(
					'id'				=> $value['id'],
					'label'		=> $value['nama_perusahaan'] . ' - ' . $regency->name . ' - ' . $province->name
				);
			} else {
				$clean[$key] = array(
					'id'				=> $value['id'],
					'nama_perusahaan'	=> $value['nama_perusahaan'],
					'show_search'		=> $value['nama_perusahaan'] . ' - ' . $regency->name . ' - ' . $province->name
				);
			}
		}

		$transformed = array(
			'data' => $clean
		);

		return ResponseOK($transformed);
	}
}
