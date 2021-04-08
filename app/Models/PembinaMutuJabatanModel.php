<?php namespace App\Models;

use CodeIgniter\Model;

class PembinaMutuJabatanModel extends Model {
    protected $table                = 'tbl_pembina_mutu_jabatan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'jabatan', 'unit_kerja', 'instansi', 'tahun_mulai', 'tahun_selesai', 'masih_menjabat', 'pembina_mutu_id' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $db;
}