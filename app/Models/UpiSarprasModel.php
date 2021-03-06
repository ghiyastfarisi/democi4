<?php namespace App\Models;

use CodeIgniter\Model;

class UpiSarprasModel extends Model {
    protected $table                = 'tbl_upi_sarpras';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'upi_id', 'sarpras_id', 'nilai_unit', 'nilai_kapasitas' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $beforeInsert         = [];
    protected $beforeUpdate         = [];
    protected $db;
}