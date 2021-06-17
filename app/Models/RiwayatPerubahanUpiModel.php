<?php namespace App\Models;

use CodeIgniter\Model;

class RiwayatPerubahanUpiModel extends Model {
    protected $table                = 'tbl_riwayat_perubahan_upi';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'perubahan_upi_id', 'catatan' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
}