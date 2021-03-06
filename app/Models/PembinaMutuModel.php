<?php namespace App\Models;

use CodeIgniter\Model;

class PembinaMutuModel extends Model {
    protected $table                = 'tbl_pembina_mutu';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'email', 'nama_lengkap', 'nip', 'foto_profil', 'keahlian', 'deskripsi', 'no_hp', 'user_id' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $db;
}