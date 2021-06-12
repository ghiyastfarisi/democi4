<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblUser extends Migration
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
			'username' 			=> [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '255',
				'null' 			=> false,
			],
			'password' => [
				'type' => 'TEXT',
				'null' => false,
			],
			'login_status' => [
				'type' 			=> 'ENUM',
				'null' 			=> false,
				'constraint' 	=> [ 'active', 'inactive' ],
				'default' 		=> 'inactive'
			],
			'registration_token' => [
				'type' => 'TEXT',
				'null' => false
			],
			'generated_token' => [
				'type' => 'TEXT',
				'null' => false
			],
			'role' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '255',
				'null' 			=> false,
				'default'		=> 'pembina_mutu' // admin & pembina_mutu (all users must use KKP email)
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
		$this->forge->createTable('tbl_user');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_user');
	}
}
