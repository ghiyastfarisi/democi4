<?php namespace App\Models;

use CodeIgniter\Model;

class PembinaMutuPelatihanModel extends Model {
    protected $table                = 'tbl_pembina_mutu_pelatihan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'nama_pelatihan', 'penyelenggara', 'tahun_pelaksanaan', 'pembina_mutu_id' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $db;
}