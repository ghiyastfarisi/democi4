<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class ProduksiPemasaranModel
{
    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('tbl_produksi_pemasaran');
    }

    public function GetPemasaranByProduksiIdAndType($id = 0, $type = 'domestik')
    {
        if ($id == 0) {
            return false;
        }

        $this->builder->where(['upi_produksi_id' => $id]);
        $this->builder->where('tbl_produksi_pemasaran.tipe_pemasaran', $type);

        if ($type == 'domestik') {
            $this->builder->join('tbl_location', 'tbl_location.id = tbl_produksi_pemasaran.target_pemasaran');
        } else if ($type == 'ekspor') {
            $this->builder->join('tbl_country', 'tbl_country.id = tbl_produksi_pemasaran.target_pemasaran');
        }

        $this->builder->where('(tbl_produksi_pemasaran.deleted_at = "0000-00-00 00:00:00" OR tbl_produksi_pemasaran.deleted_at IS NULL)');

        return $this->builder->get()->getResult();
    }
}