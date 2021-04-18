<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblupitenagakerja extends Migration
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
			'karyawan_tetap_l' => [
				'type'			=> 'INT',
				'constraint'    => 5,
				'unsigned'      => true,
				'null'			=> false
			],
			'karyawan_tetap_p' => [
				'type'			=> 'INT',
				'constraint'    => 5,
				'unsigned'      => true,
				'null'			=> false
			],
			'karyawan_harian_l' => [
				'type'			=> 'INT',
				'constraint'    => 5,
				'unsigned'      => true,
				'null'			=> false
			],
			'karyawan_harian_p' => [
				'type'			=> 'INT',
				'constraint'    => 5,
				'unsigned'      => true,
				'null'			=> false
			],
			'hari_kerja_bulan' => [
				'type'			=> 'INT',
				'constraint'    => 2,
				'unsigned'      => true,
				'null'			=> false
			],
			'shift_kerja_hari' => [
				'type'			=> 'INT',
				'constraint'    => 1,
				'unsigned'      => true,
				'null'			=> false
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
		$this->forge->createTable('tbl_upi_tenaga_kerja');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_upi_tenaga_kerja');
	}
}
