<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblFileKunjungan extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type'           => 'BIGINT',
				'constraint'     => 20,
				'unsigned'       => true,
				'auto_increment' => true
			],
			'file_upload_id' => [
				'type'          => 'BIGINT',
				'constraint'    => 20,
				'unsigned'      => true
			],
			'kunjungan_id' => [
				'type'          => 'BIGINT',
				'constraint'    => 20,
				'unsigned'      => true
			],
			'file_type' => [
				'type'          => 'ENUM',
				'null'			=> false,
				'constraint'   	=> [ 'document', 'image' ],
				'default'		=> 'image'
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
		$this->forge->createTable('tbl_file_kunjungan');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_file_kunjungan');
	}
}
