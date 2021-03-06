<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPembinaMutuKeahlian extends Migration
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
			'pembina_mutu_id' 		=> [
				'type'           	=> 'BIGINT',
				'constraint'     	=> 20,
				'unsigned'       	=> true
			],
			'badge_id'				=> [
				'type'           	=> 'BIGINT',
				'constraint'     	=> 20,
				'unsigned'       	=> true
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
		$this->forge->createTable('tbl_pembina_mutu_keahlian');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_pembina_mutu_keahlian');
	}
}
