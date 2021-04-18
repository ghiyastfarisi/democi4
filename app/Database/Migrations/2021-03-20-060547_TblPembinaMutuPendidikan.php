<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPembinaMutuPendidikan extends Migration
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
			'jenjang' => [
				'type'			=> 'ENUM',
				'constraint'    => [ 'D3', 'D4', 'S1', 'S2', 'S3' ],
				'null' 			=> false,
			],
			'institusi_pendidikan' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false,
			],
			'program_studi' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false,
			],
			'tahun_lulus' => [
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
		$this->forge->addForeignKey('pembina_mutu_id','tbl_pembina_mutu','id','CASCADE','CASCADE');
		$this->forge->createTable('tbl_pembina_mutu_pendidikan');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_pembina_mutu_pendidikan');
	}
}
