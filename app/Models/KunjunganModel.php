<?php namespace App\Models;

use CodeIgniter\Model;

class KunjunganModel extends Model {
    protected $table                = 'tbl_kunjungan';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'pembina_mutu_id', 'upi_id', 'tanggal_kunjungan', 'kegiatan', 'catatan' ];
    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $beforeInsert         = [ 'cleanNotes' ];
    protected $beforeUpdate         = [ 'cleanNotes' ];

    function SaveKunjunganFileUpload($batchData)
    {
        $fkb = $this->db->table('tbl_file_kunjungan');
        return $fkb->insertBatch($batchData);
    }

    protected function cleanNotes(array $data)
    {
        $data['data']['catatan'] = htmlentities($data['data']['catatan']);

        return $data;
    }

}