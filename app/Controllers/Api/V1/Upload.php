<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
// use App\Models\FileModel;

class Upload extends BaseController
{
	protected $KunjunganModel;
	protected $validation;
	protected $db;

	function __construct()
	{
		// $this->KunjunganModel = new KunjunganModel();
		$this->validation = \Config\Services::validation();
		$this->db = db_connect();not found
	}

	public function file()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$uploadFiles = $req->getFiles();
		$uploadType = $req->getPost('type');
		$uploaded = array();

		foreach($uploadFiles['files'] as $file)
		{
			if ($file->isValid() && ! $file->hasMoved())
			{
				$newName = $file->getRandomName();
				$file->move('./files', $newName);
				array_push($uploaded, '/files/'.$newName);
			}
		}

		// $this->validation->setRules(
		// 	$this->kunjunganField['validation'],
		// 	$this->kunjunganField['message'],
		// );

		// $reqArray = (array) $req->getJSON();

		// if(!$this->validation->run($reqArray)) {
		// 	return ResponseError(400, array('message' => $this->validation->getErrors()));
		// }

		// $UpiModel = new UpiModel();
		// $upi = $UpiModel->find($reqArray['upi_id']);

		// if ($upi == NULL) {
		// 	return ResponseNotFound();
		// }

		// $pembinaMutuId = 10;

		// $insert = array_merge(
		// 	$reqArray,
		// 	array(
		// 		'pembina_mutu_id'	=> $pembinaMutuId
		// 	)
		// );

		// $this->KunjunganModel->save($insert);

		return ResponseCreated(array( 'message' => 'file uploaded', 'path' => $uploaded ));
	}
}
