<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPembinaMutuPelatihan extends Migration
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
			'nama_pelatihan' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false,
			],
			'penyelenggara' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false,
			],
			'tahun_pelaksanaan' => [
				'type'			=> 'INT',
				'constraint'    => 4,
				'null' 			=> false,
			],
			'pembina_mutu_id' => [
				'type' 			=> 'BIGINT',
				'constraint' 	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'created_at' => [
				'type'	=> 'DATETIME',
				'null'	=> false
			],
			'updated_at' => [
				'type'		=> 'DATETIME',
				'null'		=> false
			],
			'deleted_at' => [
				'type'	=> 'DATETIME',
				'null'	=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_pembina_mutu_pelatihan');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_pembina_mutu_pelatihan');
	}
}
