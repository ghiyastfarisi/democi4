<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblkunjungan extends Migration
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
			'pembina_mutu_id' => [
				'type'           => 'BIGINT',
				'constraint'     => 20,
				'unsigned'       => true
			],
			'upi_id' => [
				'type'           => 'BIGINT',
				'constraint'     => 20,
				'unsigned'       => true
			],
			'tanggal_kunjungan' => [
				'type'			=> 'DATETIME',
				'null'			=> false
			],
			'kegiatan' 			=> [
				'type'			=> 'TEXT',
				'null'			=> false
			],
			'catatan' 			=> [
				'type'			=> 'TEXT',
				'null'			=> false
			],
			'created_at' 		=> [
				'type'			=> 'DATETIME',
				'null'			=> false
			],
			'updated_at' 		=> [
				'type'			=> 'DATETIME',
				'null'			=> false
			],
			'deleted_at' 		=> [
				'type'			=> 'DATETIME',
				'null'			=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_kunjungan');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_kunjungan');
	}
}
