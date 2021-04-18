<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblupisarpras extends Migration
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
			'upi_id' => [
				'type'			=> 'BIGINT',
				'constraint'	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'sarpras_id' => [
				'type'			=> 'BIGINT',
				'constraint'	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'nilai_unit' => [
				'type'			=> 'INT',
				'constraint'    => 11,
				'default'		=> 0
			],
			'nilai_kapasitas' => [
				'type'			=> 'INT',
				'constraint'    => 11,
				'default'		=> 0
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
		$this->forge->createTable('tbl_upi_sarpras');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_upi_sarpras');
	}
}
