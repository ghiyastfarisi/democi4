<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tbllocation extends Migration
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
			'uid' => [
				'type'           => 'BIGINT',
				'constraint'     => 20,
				'unsigned'       => true,
			],
			'name' => [
				'type'          => 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false
			],
			'alias' => [
				'type'          => 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false
			],
			'level' => [
				'type'          => 'INT',
				'constraint'    => 1,
				'default'		=> 0
			],
			'parent' => [
				'type'          => 'BIGINT',
				'constraint'    => 20,
				'default'		=> 0
			],
			'type' => [
				'type'          => 'ENUM',
				'constraint'    => [ 'province', 'regency', 'district', 'sub_district' ],
				'null'			=> false
			],
			'zipcode' => [
				'type'          => 'VARCHAR',
				'constraint'    => 5,
				'null' 			=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_location');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_location');
	}
}
