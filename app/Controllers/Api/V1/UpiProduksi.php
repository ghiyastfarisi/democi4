<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UpiProduksiModel;
use App\Models\ProdukModel;
use App\Models\ProduksiProdukModel;
use App\Models\ProduksiPemasaranModel;

class UpiProduksi extends BaseController
{
	protected $UpiProduksiModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		$this->UpiProduksiModel = new UpiProduksiModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();
	}

	public function GetByUpi($id = 0)
	{
		$req = $this->request;

		if (0 === $id) {
			return ResponseNotFound();
		}

		if ($req->getMethod(TRUE) !== 'GET') {
			return ResponseNotAllowed();
		}

		$q = $req->getGet();

		$getProdukName = isset($q['getProdukName']) && filter_var($q['getProdukName'], FILTER_VALIDATE_BOOLEAN) ? true : false;
		$getProdukProduksi = isset($q['getProdukProduksi']) && filter_var($q['getProdukProduksi'], FILTER_VALIDATE_BOOLEAN) ? true : false;
		$getPemasaran = isset($q['getPemasaran']) && filter_var($q['getPemasaran'], FILTER_VALIDATE_BOOLEAN) ? true : false;

		$resp = $this->UpiProduksiModel->where('upi_id', $id)->first();

		if (null !== $resp) {
			if ($getProdukName) {
				$produkModel = new ProdukModel();

				$resp['produk_utama_detail'] = $produkModel->GetProductById($resp['produk_utama']);
			}
			if ($getProdukProduksi) {
				$produksiProdukModel = new ProduksiProdukModel();

				$resp['produk_dihasilkan'] = $produksiProdukModel->GetProdukByProduksiId($resp['id']);
			}
			if ($getPemasaran) {
				$produksiPemasaranModel = new ProduksiPemasaranModel();

				$resp['pemasaran_mancanegara'] = $produksiPemasaranModel->GetPemasaranByProduksiIdAndType($resp['id'], 'ekspor');
				$resp['pemasaran_domestik'] = $produksiPemasaranModel->GetPemasaranByProduksiIdAndType($resp['id'], 'domestik');
			}
		}

		$transformed = array(
			'data' => $resp
		);

		return ResponseOK($transformed);
	}
}
