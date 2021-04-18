<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SarprasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'          => 'Gudang Beku',
                'alias'         => 'Cold Storage',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Chest Freezer',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'ABF',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'CPF',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Alat Pembekuan Lain',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Gudang Kering',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Alat atau Area Pengering',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Alat Pengasapan',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Retort',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Seamer',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ],
            [
                'name'          => 'Bak Penampungan Ikan Segar',
                'alias'         => '',
                'created_at'    => Time::Now(),
                'updated_at'    => Time::Now()
            ]
        ];

        // Using Query Builder
        $this->db->table('tbl_sarpras')->insertBatch($data);
    }
}