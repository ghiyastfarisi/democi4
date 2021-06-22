<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UpiProduksiSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('tbl_upi_produksi')->insert([
            'upi_id'                    => 1,
            'merk_dagang'               => 'Moeghifar Seafood Mania',
            'produk_utama'              => 163,
            'kapasitas_produksi_hari'   => 34000,
            'rata_hari_produksi_bulan'  => 25,
            'rata_bulan_produksi_tahun' => 2,
            'created_at'                => Time::Now(),
            'updated_at'                => Time::Now()
        ]);
        $this->db->table('tbl_upi_tenaga_kerja')->insert([
            'upi_id'            => 1,
            'karyawan_tetap_l'  => 40,
            'karyawan_tetap_p'  => 10,
            'karyawan_harian_l' => 3,
            'karyawan_harian_p' => 7,
            'hari_kerja_bulan'  => 25,
            'shift_kerja_hari'  => 2,
            'created_at'        => Time::Now(),
            'updated_at'        => Time::Now()
        ]);
        $this->db->table('tbl_upi_sarpras')->insertBatch([
            [
                'upi_id'            => 1,
                'sarpras_id'        => 1,
                'nilai_unit'        => 6,
                'nilai_kapasitas'   => 600,
                'ukuran'            => 'kg',
                'created_at'        => Time::Now(),
                'updated_at'        => Time::Now()
            ], [
                'upi_id'            => 1,
                'sarpras_id'        => 2,
                'nilai_unit'        => 6,
                'nilai_kapasitas'   => 10,
                'ukuran'            => 'ton',
                'created_at'        => Time::Now(),
                'updated_at'        => Time::Now()
            ], [
                'upi_id'            => 1,
                'sarpras_id'        => 3,
                'nilai_unit'        => 20,
                'nilai_kapasitas'   => 2000,
                'ukuran'            => 'kg',
                'created_at'        => Time::Now(),
                'updated_at'        => Time::Now()
            ], [
                'upi_id'            => 1,
                'sarpras_id'        => 4,
                'nilai_unit'        => 44,
                'nilai_kapasitas'   => 440000,
                'ukuran'            => 'kg',
                'created_at'        => Time::Now(),
                'updated_at'        => Time::Now()
            ]
        ]);
        $this->db->table('tbl_produksi_produk')->insertBatch([
            [
                'upi_produksi_id'           => 1,
                'produk_id'                 => 162,
                'created_at'                => Time::Now(),
                'updated_at'                => Time::Now()
            ],
            [
                'upi_produksi_id'           => 1,
                'produk_id'                 => 163,
                'created_at'                => Time::Now(),
                'updated_at'                => Time::Now()
            ],
            [
                'upi_produksi_id'           => 1,
                'produk_id'                 => 164,
                'created_at'                => Time::Now(),
                'updated_at'                => Time::Now()
            ]
        ]);
        $this->db->table('tbl_produksi_pemasaran')->insertBatch([
            [
                'upi_produksi_id'           => 1,
                'target_pemasaran'          => 76,
                'tipe_pemasaran'            => 'ekspor',
                'created_at'                => Time::Now(),
                'updated_at'                => Time::Now()
            ],
            [
                'upi_produksi_id'           => 1,
                'target_pemasaran'          => 83,
                'tipe_pemasaran'            => 'ekspor',
                'created_at'                => Time::Now(),
                'updated_at'                => Time::Now()
            ],
            [
                'upi_produksi_id'           => 1,
                'target_pemasaran'          => 213,
                'tipe_pemasaran'            => 'domestik',
                'created_at'                => Time::Now(),
                'updated_at'                => Time::Now()
            ]
        ]);
    }
}