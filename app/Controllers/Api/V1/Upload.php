<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\FileUploadModel;
use App\Models\KunjunganModel;

class Upload extends BaseController
{
	protected $FileUploadModel;
	protected $validation;
	protected $db;
	protected $uploadUsageFolder = [
		'gambar_laporan' 	=> array('laporan', 'gambar'),
		'dokumen_laporan' 	=> array('laporan', 'dokumen'),
		'foto_pabrik'		=> array('upi', 'pabrik'),
		'produk_upi'		=> array('upi', 'produk'),
	];
	protected $validType = [
		'image_upload'   	=> array(
			'image/png' 	=> true,
			'image/jpg' 	=> true,
			'image/jpeg'	=> true
		),
		'document_upload' 	=> array(
			'application/pdf' => true,
			'application/zip' => true,
			'application/vnd.rar' => true,
			'application/vnd.ms-powerpoint' => true,
			'application/vnd.openxmlformats-officedocument.presentationml.presentation' => true,
			'application/msword' => true,
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => true,
			'application/vnd.ms-excel' => true,
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => true
		)
	];

	function __construct()
	{
		$this->FileUploadModel = new FileUploadModel();
		$this->validation = \Config\Services::validation();
	}

	public function file()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$savePath = array('files');
		$uploadType = $req->getPost('upload_type');
		$uploadUsage = $req->getPost('upload_usage');
		$kunjunganId = $req->getPost('kunjunganId');
		$reqFileName = $req->getPost('file_names');

		$fileNames = isset($reqFileName) > 0 ? explode(',', $reqFileName) : [];

		$validTypes = array();

		if (isset($this->uploadUsageFolder[$uploadUsage]))
		{
			$savePath = array_merge($savePath, $this->uploadUsageFolder[$uploadUsage]);
		} else {
			return ResponseError(400, array('message' => 'usage is required'));
		}

		if (isset($this->validType[$uploadType]))
		{
			$validTypes = $this->validType[$uploadType];
		} else {
			return ResponseError(400, array('message' => 'invalid upload type'));
		}

		$uploadFiles = $req->getFiles();
		$uploaded = array();

		if (($uploadUsage == 'gambar_laporan' || $uploadUsage == 'dokumen_laporan') && (!isset($kunjunganId) || $kunjunganId < 1)) {
			return ResponseError(400, array('message' => 'missing ID kunjungan'));
		}

		// validate
		foreach($uploadFiles['files'] as $file)
		{
			if ($file->isValid() && !$file->hasMoved())
			{
				$fileType = $file->getClientMimeType();
				$oriname = $file->getName();

				if (!isset($validTypes[$fileType])) {
					return ResponseError(400, array('message' => 'tipe file tidak valid: '.$oriname.' => '.$fileType));
				}
			} else {
				return ResponseError(400, array('message' => $file->getErrorString().'('.$file->getError().')'));
			}
		}

		// upload
		foreach($uploadFiles['files'] as $k => $file)
		{
			if ($file->isValid() && !$file->hasMoved())
			{
				$fileType = $file->getClientMimeType();
				$saveLocation = implode('/', $savePath);
				$safeName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($file->getName()));

				$newName = $file->getRandomName();
				$file->move('./'.$saveLocation, $newName);

				array_push($uploaded, array(
					'file_name'		=> (isset($fileNames[$k]) && $fileNames[$k] != '') ? $fileNames[$k] : $safeName,
					'upload_path' 	=> $saveLocation.'/'.$newName,
					'file_type'		=> $fileType,
					'usage'			=> $uploadUsage,
				));
			}
		}

		$savedIds = array();

		// save db
		foreach($uploaded as $k => $v) {
			$this->FileUploadModel->insert($v);

			if ($this->FileUploadModel->insertID() > 0) {
				array_push($savedIds, $this->FileUploadModel->insertID());
			}
		}

		if ($uploadUsage == 'gambar_laporan') {
			$this->_PostActionUploadKunjungan($savedIds, $kunjunganId, 'image');
		}

		if ($uploadUsage == 'dokumen_laporan') {
			$this->_PostActionUploadKunjungan($savedIds, $kunjunganId, 'document');
		}

		return ResponseCreated(array( 'message' => 'file uploaded', 'path' => $uploaded, 'ids' => $savedIds ));
	}

	function _PostActionUploadKunjungan($savedIds = array(), $kunjunganId = 0, $tipeData = 'image')
	{
		$batchPayload = array();
		foreach($savedIds as $v) {
			array_push($batchPayload, array(
				'file_upload_id' 	=> $v,
				'kunjungan_id'		=> $kunjunganId,
				'file_type'			=> $tipeData,
				'created_at'		=> date('Y-m-d H:i:s'),
				'updated_at'		=> date('Y-m-d H:i:s')
			));
		}

		$km = new KunjunganModel();
		$km->SaveKunjunganFileUpload($batchPayload);

		return true;
	}
}
