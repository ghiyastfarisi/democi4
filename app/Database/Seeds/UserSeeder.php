<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'              => 'moeghifar@gmail.com',
            'password'              => '$2y$10$DtT1dSw9m8w9ROcAskbSgunKIuV4yYdRI1ztr9/KvNF8tHFWrMi5i',
            'login_status'          => 'active',
            'role'                  => 'admin',
            'registration_token'    => '1c35df098cec1a4b55ff1cafa22b5d3c1ef073d217c8deb4b09768e6b6626a7b',
            'registration_status'   => 'verified',
            'generated_token'       => 'f5ec6fd87dcb9848a7071150609c3657c56ca3c1d294665413c90325729c9373',
            'created_at'            => Time::Now(),
            'updated_at'            => Time::Now()
        ];

        // Using Query Builder
        $this->db->table('tbl_user')->insert($data);

        $data = [
            'nama_lengkap'  => 'moe ghi far',
            'nip'           => '19999999991',
            'no_hp'         => '08199999991',
            'email'         => 'moeghifar@gmail.com',
            'user_id'       => 1,
            'created_at'    => Time::Now(),
            'updated_at'    => Time::Now()
        ];

        // Using Query Builder
        $this->db->table('tbl_pembina_mutu')->insert($data);
    }
}