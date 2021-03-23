<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblPembinaMutu extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'          => [
				'type'           => 'BIGINT',
				'constraint'     => 20,
				'unsigned'       => true,
				'auto_increment' => true,
			],
			'nama_lengkap' 			=> [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '255',
				'null' 			=> false,
			],
			'nip' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '100',
				'null' 			=> false
			],
			'foto_profil' => [
				'type' 			=> 'TEXT',
				'null' 			=> true
			],
			'keahlian' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '100',
				'null' 			=> false
			],
			'deskripsi' => [
				'type' 			=> 'TEXT',
				'null' 			=> true
			],
			'email' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '100',
				'null' 			=> false
			],
			'no_hp' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '15',
				'null' 			=> false
			],
			'user_id' => [
				'type' 			=> 'BIGINT',
				'constraint' 	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'created_at' => [
				'type'	=> 'DATETIME',
				'null'	=> false
			],
			'updated_at' => [
				'type'		=> 'DATETIME',
				'null'		=> false
			],
			'deleted_at' => [
				'type'	=> 'DATETIME',
				'null'	=> true
			]
		]);
		$this->forge->addKey('id', true);
		$this->$forge->addForeignKey('user_id','tbl_user','id','CASCADE','CASCADE');
		$this->forge->createTable('tbl_pembina_mutu');
	}

	public function down()
	{
		$this->forge->createTable('tbl_pembina_mutu');
	}
}
