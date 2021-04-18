<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblupiproduksipemasaran extends Migration
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
			'target_pemasaran' => [
				'type'			=> 'BIGINT',
				'constraint'	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'tipe_pemasaran' => [
				'type' 			=> 'ENUM',
				'null' 			=> true,
				'constraint' 	=> [ 'domestik', 'ekspor' ]
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
		$this->forge->createTable('tbl_produksi_pemasaran');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_produksi_pemasaran');
	}
}
