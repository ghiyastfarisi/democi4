<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblsarpras extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type'           => 'BIGINT',
				'constraint'     => 20,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'name' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null'			=> false
			],
			'alias' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'default'		=> ''
			],
			'created_at' => [
				'type'	=> 'DATETIME',
				'null'	=> false
			],
			'updated_at' => [
				'type'	=> 'DATETIME',
				'null'	=> false
			],
			'deleted_at' => [
				'type'	=> 'DATETIME',
				'null'	=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_sarpras');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_sarpras');
	}
}
