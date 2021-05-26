<?php namespace App\Models;

use CodeIgniter\Model;

class KunjunganModel extends Model {
    protected $table                = 'tbl_kunjungan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'pembina_mutu_id', 'upi_id', 'tanggal_kunjungan', 'kegiatan' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $db;
}