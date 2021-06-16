<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblRiwayatPerubahanUpi extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type'           	=> 'BIGINT',
				'constraint'     	=> 20,
				'unsigned'       	=> true,
				'auto_increment' 	=> true,
			],
			'perubahan_upi_id' 		=> [
				'type'           	=> 'BIGINT',
				'constraint'     	=> 20,
				'unsigned'       	=> true
			],
			'catatan'				=> [
				'type'				=> 'TEXT',
				'null'				=> false
			],
			'created_at' 			=> [
				'type'				=> 'DATETIME',
				'null'				=> false
			],
			'updated_at' 			=> [
				'type'				=> 'DATETIME',
				'null'				=> false
			],
			'deleted_at' 			=> [
				'type'				=> 'DATETIME',
				'null'				=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_riwayat_perubahan_upi');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_riwayat_perubahan_upi');
	}
}
