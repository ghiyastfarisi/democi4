<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\FileUploadModel;

class Upload extends BaseController
{
	protected $FileUploadModel;
	protected $validation;
	protected $db;
	protected $uploadUsageFolder = [
		'gambar_laporan' 	=> array('laporan', 'gambar'),
		'dokumen_laporan' 	=> array('laporan', 'dokumen'),
		'profil_upi'		=> array('upi', 'profil'),
		'produk_upi'		=> array('upi', 'produk'),
	];
	protected $validType = [
		'image_upload'   	=> array(
			'image/png' 	=> true,
			'image/jpg' 	=> true,
			'image/jpeg'	=> true
		),
		'document_upload' 	=> array(
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/pdf'
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

		// validate
		foreach($uploadFiles['files'] as $file)
		{
			if ($file->isValid() && !$file->hasMoved())
			{
				$fileType = $file->getClientMimeType();
				$oriname = $file->getName();

				if (!isset($validTypes[$fileType])) {
					return ResponseError(400, array('message' => 'tipe file tidak valid: '.$oriname));
				}
			} else {
				return ResponseError(400, array('message' => $file->getErrorString().'('.$file->getError().')'));
			}
		}

		// upload
		foreach($uploadFiles['files'] as $file)
		{
			if ($file->isValid() && !$file->hasMoved())
			{
				$fileType = $file->getClientMimeType();
				$newName = $file->getRandomName();
				$saveLocation = implode('/', $savePath);
				$file->move('./'.$saveLocation, $newName);
				array_push($uploaded, array(
					'upload_path' 	=> $saveLocation.'/'.$newName,
					'file_type'		=> $fileType,
					'usage'			=> $uploadUsage
				));
			}
		}

		$this->FileUploadModel->insertBatch($uploaded);

		return ResponseCreated(array( 'message' => 'file uploaded', 'path' => $uploaded ));
	}
}
