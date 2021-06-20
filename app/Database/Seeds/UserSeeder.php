<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'      => 'moeghifar@gmail.com',
            'password'      => 'wakawakaee',
            'login_status'  => 'active',
            'role'          => 'admin',
            'created_at'    => Time::Now(),
            'updated_at'    => Time::Now()
        ];

        // Using Query Builder
        $this->db->table('tbl_user')->insert($data);
    }
}