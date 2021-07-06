<?php namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PembinaMutuModel;

class User extends BaseController
{
	protected $UserModel;
	protected $validation;

	function __construct()
	{
		$this->UserModel = new UserModel();
		$this->validation = \Config\Services::validation();
	}

	public function index()
	{
		return 'api user v1 here...';
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

		$keyword = isset($q['keyword']) ? $q['keyword'] : '';

		$queryBuilder = $this->UserModel;
		$countQueryBuilder = new UserModel();

		if ($keyword != '') {
			$cleanKey = "'%" . $this->db->escapeLikeString($keyword) . "%' ESCAPE '!'";
			$where  = 'username LIKE ' . $cleanKey;
			$queryBuilder->where($where);
			$countQueryBuilder->where($where);
		}

		$queryBuilder->whereNotIn('id', [1]);
		$countQueryBuilder->whereNotIn('id', [1]);

		$resp = $queryBuilder->orderBy('id', 'desc')->findAll($limit, $offset);
		$countQuery = $countQueryBuilder->countAllResults();
		$count = (int)$countQuery;

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

		$resp = $this->UserModel->find($id);

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

		$foundRecord = $this->UserModel->where('username', $reqArray['username'])->selectCount('id')->find();

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

		$this->UserModel->save($insert);

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
			'login_status' 	=> 'in_list[active,inactive]',
			'role'			=> 'in_list[admin,pembina_mutu]'
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
			],
			'role' => [
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
			$foundRecord = $this->UserModel->where('username', $reqArray['username'])->selectCount('id')->find($id);

			if (count($foundRecord) > 0 && (int)$foundRecord[0]['id'] > 0) {
				return ResponseConflict(array('message' => 'username already registered'));
			}
		}

		// check old id and generated token
		$user = $this->UserModel->find($id);

		if (!isset($user)) {
			return ResponseError(400, array('message' => 'user invalid'));
		}

		$reqArray['id'] = $id;
		$reqArray['generated_token'] = $user['generated_token'];

		$this->UserModel->save($reqArray);

		return ResponseOK(array( 'message' => 'user updated' ));
	}

	public function delete($id = 0) {
		if ($id < 1) {
			return ResponseNotFound();
		}

		$deleted = $this->UserModel->delete($id);

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

		$foundRecord = $this->UserModel->where('username', $reqArray['username'])->selectCount('id')->find();

		if (count($foundRecord) > 0 && (int)$foundRecord[0]['id'] > 0) {
			return ResponseConflict(array('message' => 'username already registered'));
		}

		$regToken = hash('sha256', 'registration_token'.sha1($reqArray['username'].microtime()));
		$genToken = hash('sha256', 'generated_token'.sha1($reqArray['username'].microtime()));

		$reqArray['nama_lengkap'] = ToTittleCase($reqArray['nama_lengkap']);

		$insert = array_merge(
			$reqArray,
			array(
				'login_status' 			=> 'inactive',
				'registration_token' 	=> $regToken,
				'generated_token' 		=> $genToken,
				'registration_status'	=> 'unverified'
			)
		);

		$saved = $this->UserModel->save($insert);

		if ($saved) {
			$puModel = new PembinaMutuModel();

			$user_id = $this->UserModel->getInsertID();

			$insertPembinaMutu = array_merge(
				$reqArray,
				array(
					'user_id' 	=> (int)$user_id,
					'email'		=> $reqArray['username']
				)
			);

			$puModel->save($insertPembinaMutu);

			$this->_sendVerifMail($reqArray['username'], $reqArray['nama_lengkap'], $regToken);
		}

		return ResponseCreated(array( 'message' => 'user and pembina mutu created' ));
	}

	public function register()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		return $this->createPembinaMutu();
	}

	public function login()
	{
		$req = $this->request;

		if ($req->getMethod(TRUE) !== 'POST') {
			return ResponseNotAllowed();
		}

		$this->validation->setRules([
			'username' => 'required|valid_email',
			'password' => 'required'
		], [
			'username' => [
				'required' 		=> 'wajib diisi',
				'valid_email'	=> 'harap gunakan format email'
			],
			'password' => [
				'required' 		=> 'wajib diisi'
			]
		]);

		$reqArray = (array) $req->getJSON();

		if(!$this->validation->run($reqArray)) {
			return ResponseError(400, array('message' => $this->validation->getErrors()));
		}

		$foundRecord = $this->UserModel
			->select('tbl_user.*, tbl_pembina_mutu.id as pmid')
			->join('tbl_pembina_mutu', 'tbl_pembina_mutu.user_id = tbl_user.id')
			->where('tbl_user.username', $reqArray['username'])->get()->getRow();

		if (null == $foundRecord || (int)$foundRecord->id == 0) {
			return ResponseError(403, array('message' => 'user belum terdaftar'));
		}

		if ($foundRecord->login_status!=='active')
		{
			return ResponseError(403, array('message' => 'user belum aktif'));
		}

		$pass = $reqArray['password'].$foundRecord->generated_token;

		$valid = password_verify($pass, $foundRecord->password);

		if (!$valid) {
			return ResponseError(403, array('message' => 'email atau password salah'));
		}

		$session = array(
            'login'     => true,
            'uid'       => $foundRecord->id,
            'pmid'      => $foundRecord->pmid,
            'username'  => $foundRecord->username,
            'role'      => $foundRecord->role,
        );

		$this->SetSession($session);

		return ResponseOK(array( 'data' => $session  ));
	}

	function rv($regisToken) {
		$foundRecord = $this->UserModel
			->select('tbl_user.id, tbl_user.registration_status, tbl_user.registration_token, tbl_user.login_status')
			->join('tbl_pembina_mutu', 'tbl_pembina_mutu.user_id = tbl_user.id')
			->where('tbl_user.registration_token', $regisToken)->get()->getRow();


		if ($foundRecord->registration_status === 'unverified' && $regisToken === $foundRecord->registration_token)
		{
			// verify registration and set active
			$this->UserModel->save(array(
				'id'					=> $foundRecord->id,
				'login_status'			=> 'active',
				'registration_status'	=> 'verified'
			));

			$args = array(
				'_PageSectionTitle' 	=> '',
				'_PageSectionSubTitle' 	=> ''
			);

			$data = array(
				'args' 			=> $args,
				'_PageTitle' 	=> 'rv',
				'_Pages' 		=> '',
				'_LoadCSS'		=> array(),
				'_LoadJS'		=> array(),
				'_ActiveSlug'	=> '',
				'_ExtendPath'	=> 'rv'
			);

			return RenderTemplate($data);
		}

		return redirect()->to('/web/login');
	}

	function _sendVerifMail($userMail, $fullName, $regisToken)
	{
		$mailContent = '<!doctype html><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><title></title><!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]--><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><style type="text/css">#outlook a { padding:0; }
		body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
		table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
		img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
		p { display:block;margin:13px 0; }</style><!--[if mso]>
	  <noscript>
	  <xml>
	  <o:OfficeDocumentSettings>
		<o:AllowPNG/>
		<o:PixelsPerInch>96</o:PixelsPerInch>
	  </o:OfficeDocumentSettings>
	  </xml>
	  </noscript>
	  <![endif]--><!--[if lte mso 11]>
	  <style type="text/css">
		.mj-outlook-group-fix { width:100% !important; }
	  </style>
	  <![endif]--><style type="text/css">@media only screen and (min-width:480px) {
	  .mj-column-per-100 { width:100% !important; max-width: 100%; }
	}</style><style media="screen and (min-width:480px)">.moz-text-html .mj-column-per-100 { width:100% !important; max-width: 100%; }</style><style type="text/css">@media only screen and (max-width:480px) {
	table.mj-full-width-mobile { width: 100% !important; }
	td.mj-full-width-mobile { width: auto !important; }
  }</style></head><body style="word-spacing:normal;"><div><!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;"><tbody><tr><td style="width:280px;"><img height="auto" src="https://binamutu.com/logo.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="280"></td></tr></tbody></table></td></tr><tr><td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;"><p style="border-top:solid 4px #BBBBBB;font-size:1px;margin:0px auto;width:100%;"></p><!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 4px #BBBBBB;font-size:1px;margin:0px auto;width:550px;" role="presentation" width="550px" ><tr><td style="height:0;line-height:0;"> &nbsp;
</td></tr></table><![endif]--></td></tr><tr><td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;"><div style="font-family:helvetica;font-size:20px;line-height:1;text-align:left;color:#333333;">Hi, <strong>{{NAME}}</strong> sang Pembina Mutu.</div></td></tr><tr><td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;"><div style="font-family:helvetica;font-size:20px;line-height:1;text-align:left;color:#333333;">Pendaftaran akun anda di website <strong>binamutu.com</strong> telah kami terima, harap klik tautan di bawah ini untuk melakukan verifikasi agar akun anda segera aktif dan dapat digunakan.</div></td></tr><tr><td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;word-break:break-word;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;"><tr><td align="center" bgcolor="#3676ff" role="presentation" style="border:none;border-radius:3px;cursor:auto;mso-padding-alt:10px 25px;background:#3676ff;" valign="middle"><a href="{{VERIF_URL}}" style="display:inline-block;background:#3676ff;color:white;font-family:Helvetica;font-size:20px;font-weight:normal;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:3px;" target="_blank">Verifikasi Pendaftaran</a></td></tr></table></td></tr><tr><td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;"><p style="border-top:solid 4px #BBBBBB;font-size:1px;margin:0px auto;width:100%;"></p><!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 4px #BBBBBB;font-size:1px;margin:0px auto;width:550px;" role="presentation" width="550px" ><tr><td style="height:0;line-height:0;"> &nbsp;
</td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></div></body></html>';

		$verifUrl = base_url('rv/'.$regisToken);

		// string replace
		$mailContent = str_replace('{{NAME}}', $fullName, $mailContent);
		$mailContent = str_replace('{{VERIF_URL}}', $verifUrl, $mailContent);

		return SendMail($userMail, 'Verifikasi Pendaftaran', $mailContent);
	}
}
