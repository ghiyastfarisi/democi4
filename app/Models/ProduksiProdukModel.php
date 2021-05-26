<?php namespace App\Models;

use CodeIgniter\Model;

class ProduksiProdukModel extends Model
{
    protected $db;
    protected $builder;
    protected $table                = 'tbl_produksi_produk';
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

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('tbl_produksi_produk');
    }

    public function GetProdukByProduksiId($id = 0)
    {
        if ($id == 0) {
            return false;
        }

        $this->builder->where(['upi_produksi_id' => $id]);
        $this->builder->where('(tbl_produksi_produk.deleted_at IS NULL OR tbl_produksi_produk.deleted_at = "0000-00-00 00:00:00")');
        $this->builder->join('tbl_produk', 'tbl_produk.id = tbl_produksi_produk.produk_id');
        return $this->builder->get()->getResult();
    }
}