<?php namespace App\Models;

use CodeIgniter\Model;

class PerubahanUpiModel extends Model {
    protected $table                = 'tbl_perubahan_upi';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'upi_id', 'pembina_mutu_id', 'kunjungan_id', 'status', 'data_perubahan', 'catatan' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $db;

}