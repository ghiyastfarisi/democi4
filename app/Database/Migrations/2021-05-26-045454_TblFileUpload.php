<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblFileUpload extends Migration
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
			'upload_path' 		=> [
				'type'          => 'TEXT',
				'null'			=> false
			],
			'file_name' 		=> [
				'type'			=> 'TEXT',
				'null'			=> false
			],
			'file_type' 		=> [
				'type'			=> 'TEXT', // img, doc
				'null'			=> false
			],
			'usage' 			=> [
				'type'			=> 'VARCHAR',
				'constraint'    => 225,
				'null'			=> true
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
		$this->forge->createTable('tbl_file_upload');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_file_upload');
	}
}
