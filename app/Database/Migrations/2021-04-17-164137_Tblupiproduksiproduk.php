<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblupiproduksiproduk extends Migration
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
			'upi_produksi_id' => [
				'type'			=> 'BIGINT',
				'constraint'	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'produk_id' => [
				'type'			=> 'BIGINT',
				'constraint'	=> 20,
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
		$this->forge->createTable('tbl_produksi_produk');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_produksi_produk');
	}
}
