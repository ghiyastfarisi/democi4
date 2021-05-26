<?php namespace App\Models;

use CodeIgniter\Model;

class UpiTenagaKerjaModel extends Model {
    protected $table                = 'tbl_upi_tenaga_kerja';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'upi_id', 'karyawan_tetap_l', 'karyawan_tetap_p', 'karyawan_harian_l', 'karyawan_tetap_p', 'hari_kerja_bulan', 'shift_kerja_hari' ];
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