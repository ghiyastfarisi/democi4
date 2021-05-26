<?php namespace App\Models;

use CodeIgniter\Model;

class UpiProduksiModel extends Model {
    protected $table                = 'tbl_upi_produksi';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'upi_produksi_id', 'produk_id' ];
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