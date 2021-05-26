<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class CountryModel
{
    protected $db;
    protected $builder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('tbl_country');
    }

    public function GetAll($transformLabel = false)
    {
        if ($transformLabel) {
            $this->builder->select('id, name as label');
        }
        return $this->builder->get()->getResultArray();
    }

    public function GetByIds($ids = array())
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