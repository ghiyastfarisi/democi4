<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPembinaMutuJabatan extends Migration
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
			'jabatan' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false,
			],
			'unit_kerja' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null' 			=> false,
			],
			'instansi' => [
				'type'			=> 'ENUM',
				'constraint'    => [ 'KKP', 'DINAS' ],
				'null' 			=> false,
			],
			'tahun_mulai' => [
				'type'			=> 'INT',
				'constraint'    => 4,
				'null' 			=> false,
			],
			'tahun_selesai' => [
				'type'			=> 'INT',
				'constraint'    => 4,
				'null' 			=> false,
			],
			'masih_menjabat' => [
				'type'			=> 'ENUM',
				'constraint'    => [ 'YA', 'TIDAK' ],
				'null' 			=> false,
				'default' 		=> 'TIDAK'
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
		$this->forge->createTable('tbl_pembina_mutu_jabatan');
	}

	public function down()
	{
		$this->forge->createTable('tbl_pembina_mutu_jabatan');
	}
}
