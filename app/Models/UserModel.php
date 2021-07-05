<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {

    protected $table                = 'tbl_user';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'username', 'password', 'login_status', 'registration_token', 'registration_status', 'generated_token', 'role' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $beforeInsert         = [ 'hashPassword' ];
    protected $beforeUpdate         = [ 'hashPassword' ];
    protected $db;

    protected function hashPassword(array $data)
    {
        helper('InternalSecurity');

        if (!isset($data['data']['password'])) return $data;

        $pass = $data['data']['password'].$data['data']['generated_token'];

        $data['data']['password'] = password_hash($pass, PASSWORD_BCRYPT);

        return $data;
    }

}