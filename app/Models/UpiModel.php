<?php namespace App\Models;

use CodeIgniter\Model;

class UpiModel extends Model {
    protected $table                = 'tbl_upi';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = true;
    protected $allowedFields        = [ 'nama_perusahaan',
                                        'alamat_pabrik',
                                        'koordinat_lokasi',
                                        'nib',
                                        'no_kusuka',
                                        'npwp',
                                        'sumber_permodalan',
                                        'deskripsi',
                                        'website',
                                        'nama_pemilik',
                                        'nama_kontak_upi',
                                        'foto_pabrik',
                                        'kelurahan_desa',
                                        'kecamatan',
                                        'kab_kota',
                                        'provinsi',
                                        'created_by',
                                        'is_main'
                                    ];
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