<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class LocationModel
{
    protected $db;
    protected $locationBuilder;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->locationBuilder = $this->db->table('tbl_location');
    }

    public function GetAll($locationType = 'province', $locationParent = 0, $transformLabel = false)
    {
        if ($transformLabel) {
            $this->locationBuilder->select('id, name as label');
        }
        if ($locationParent > 0) {
            return $this->locationBuilder->getWhere(['type' => $locationType, 'parent' => $locationParent])->getResultArray();
        } else {
            return $this->locationBuilder->getWhere(['type' => $locationType])->getResultArray();
        }
    }

    public function GetProvinceById($id = 0)
    {
        if ($id == 0) {
            return false;
        }

        return $this->locationBuilder->getWhere(['type' => 'province', 'id' => $id])->getRow();
    }

    public function GetRegencyById($id = 0)
    {
        if ($id == 0) {
            return false;
        }

        return $this->locationBuilder->getWhere(['type' => 'regency', 'id' => $id])->getRow();
    }

    public function GetRegencyByIds($ids = array())
    {
        if (count($ids) == 0) {
            return false;
        }

        return $this->locationBuilder
            ->select('id')
            ->where('type', 'regency')
            ->whereIn('id', $ids)
            ->get()
            ->getResultArray();
    }

    public function GetDistrictById($id = 0)
    {
        if ($id == 0) {
            return false;
        }

        return $this->locationBuilder->getWhere(['type' => 'district', 'id' => $id])->getRow();
    }

    public function GetSubDistrictById($id = 0)
    {
        if ($id == 0) {
            return false;
        }

        return $this->locationBuilder->getWhere(['type' => 'sub_district', 'id' => $id])->getRow();
    }

    public function GetCompleteJoinFromSubDistrict($id = 0) {
        if ($id == 0) {
            return false;
        }
        $q = 'SELECT
                tl.id as subdistrict,
                tl2.id as district,
                tl3.id as regency,
                tl4.id as province
            FROM tbl_location tl
            JOIN tbl_location tl2
            ON tl.parent = tl2.id
            JOIN tbl_location tl3
            ON tl2.parent = tl3.id
            JOIN tbl_location tl4
            ON tl3.parent = tl4.id
            WHERE
                tl.level = ? AND tl.id = ?
        ';

        return $this->db->query($q, [4, $id])->getRow();
    }
}