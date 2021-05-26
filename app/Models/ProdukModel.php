<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class ProdukModel
{
    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('tbl_produk');
    }

    public function GetAll($transformLabel = false)
    {
        if ($transformLabel) {
            $this->builder->select('id, nama_produk as label');
        }
        return $this->builder->getWhere(['status_produk' => 1])->getResultArray();
    }

    public function GetProductById($id = 0)
    {
        if ($id == 0) {
            return false;
        }

        return $this->builder->getWhere(['id' => $id])->getRow();
    }

    public function GetProductByIds($ids = array())
    {
        if (count($ids) == 0) {
            return false;
        }

        return $this->builder
            ->select('id')
            ->whereIn('id', $ids)
            ->get()
            ->getResultArray();
    }
}