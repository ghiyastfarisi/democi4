<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblupiproduksi extends Migration
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
			'merk_dagang' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null'			=> true
			],
			'produk_utama' => [
				'type'			=> 'BIGINT',
				'constraint'    => 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'kapasitas_produksi_hari' => [
				'type'			=> 'INT',
				'constraint'    => 11,
				'unsigned'      => true,
				'null'			=> false
			],
			'rata_hari_produksi_bulan' => [
				'type'			=> 'INT',
				'constraint'    => 2,
				'unsigned'      => true,
				'null'			=> false
			],
			'rata_bulan_produksi_tahun' => [
				'type'			=> 'INT',
				'constraint'    => 2,
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
		$this->forge->createTable('tbl_upi_produksi');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_upi_produksi');
	}
}
