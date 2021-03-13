<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitTblUser extends Migration {
    public function up() {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'not_null'  => true,
            ],
            'password' => [
                'type'      => 'TEXT',
                'not_null'  => true,
            ],
            'created_at' => [
                'type'  => 'DATETIME',
            ],
            'updated_at' => [
                'type'  => 'DATETIME',
            ],
            'deleted_at' => [
                'type'  => 'DATETIME',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tbl_user');
    }
}
