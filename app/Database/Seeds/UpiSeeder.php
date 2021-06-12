<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UpiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama_perusahaan'           => 'Moeghifar Oceania',
            'alamat_pabrik'             => 'Jl. H Gemin Kel. Jatikramat Kec. Jatiasih',
            'koordinat_lokasi'          => '123,456',
            'nib'                       => '123000123000',
            'no_kusuka'                 => '999000999000',
            'npwp'                      => '092542943407000',
            'sertifikasi_perusahaan'    => 'HALAL',
            'sumber_permodalan'         => 'Venture Capital',
            'deskripsi'                 => 'Produsen Lobster Internasional asal Indonesia',
            'website'                   => 'moeghifaroceania.com',
            'nama_pemilik'              => 'M Ghiyast Farisi',
            'nama_kontak_upi'           => '087770000000',
            'foto_pabrik'               => 'https://images.pexels.com/photos/3635870/pexels-photo-3635870.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260',
            'kelurahan_desa'            => 39043,
            'kecamatan'                 => 3119,
            'kab_kota'                  => 217,
            'provinsi'                  => 13,
            'created_by'                => 1,
            'is_main'                   => true,
            'created_at'                => Time::Now(),
            'updated_at'                => Time::Now()
        ];

        // Using Query Builder
        $this->db->table('tbl_upi')->insert($data);
    }
}