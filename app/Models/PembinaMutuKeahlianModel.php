<?php namespace App\Models;

use CodeIgniter\Model;

class PembinaMutuKeahlianModel extends Model {
    protected $table                = 'tbl_pembina_mutu_keahlian';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'pembina_mutu_id', 'badge_id', 'deleted_at' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $db;
}