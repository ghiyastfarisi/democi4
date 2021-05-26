<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblperubahanupi extends Migration
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
				'type'           => 'BIGINT',
				'constraint'     => 20,
				'unsigned'       => true
			],
			'pembina_mutu_id' 		=> [
				'type'           	=> 'BIGINT',
				'constraint'     	=> 20,
				'unsigned'       	=> true
			],
			'status' 			=> [
				'type'			=> 'ENUM',
				'null'			=> false,
				'constraint'    => [ 'requested', 'approved', 'rejected' ],
				'default'		=> 'requested'
			],
			'data_perubahan'	=> [
				'type'			=> 'JSON',
				'null'			=> false
			],
			'created_at' 	=> [
				'type'		=> 'DATETIME',
				'null'		=> false
			],
			'updated_at' 	=> [
				'type'		=> 'DATETIME',
				'null'		=> false
			],
			'deleted_at' 	=> [
				'type'		=> 'DATETIME',
				'null'		=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbl_perubahan_upi');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_perubahan_upi');
	}
}
