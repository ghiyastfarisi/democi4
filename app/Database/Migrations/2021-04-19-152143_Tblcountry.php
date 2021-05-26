<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblcountry extends Migration
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
			'code' => [
				'type'			=> 'VARCHAR',
				'constraint'	=> 2,
				'null'			=> false
			],
			'name' => [
				'type'			=> 'VARCHAR',
				'constraint'	=> 255,
				'null'			=> false
			],
			'name_id' => [
				'type'			=> 'VARCHAR',
				'constraint'	=> 255,
				'null'			=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_country');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_country');
	}
}
