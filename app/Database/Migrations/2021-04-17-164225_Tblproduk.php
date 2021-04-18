<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblproduk extends Migration
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
			'nama_produk' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null'			=> false
			],
			'nama_produk_en' => [
				'type'			=> 'VARCHAR',
				'constraint'    => 255,
				'null'			=> true
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
		$this->forge->createTable('tbl_produk');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_produk');
	}
}
