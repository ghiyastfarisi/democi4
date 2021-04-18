<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tblupi extends Migration
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
			'nama_perusahaan' 			=> [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '255',
				'null' 			=> false,
			],
			'alamat_pabrik' => [
				'type' 			=> 'TEXT',
				'null' 			=> true
			],
			'koordinat_lokasi' => [
				'type' 			=> 'TEXT',
				'null' 			=> true
			],
			'nib' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '13',
				'null' 			=> false
			],
			'no_kusuka' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '255',
				'null' 			=> false
			],
			'npwp' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '25',
				'null' 			=> true
			],
			'sertifikasi_perusahaan' => [
				'type' 			=> 'ENUM',
				'null' 			=> true,
				'constraint' 	=> [ 'SKP', 'HACCP', 'HALAL', 'BRC', 'ISO', 'LAINNYA' ]
			],
			'sumber_permodalan' => [
				'type' 			=> 'VARCHAR',
				'constraint' 	=> '255',
				'null' 			=> false
			],
			'deskripsi' => [
				'type' 			=> 'text',
				'null' 			=> true
			],
			'website' => [
				'type' 			=> 'varchar',
				'constraint' 	=> '255',
				'null' 			=> true
			],
			'nama_pemilik' => [
				'type' 			=> 'varchar',
				'constraint' 	=> '255',
				'null' 			=> true
			],
			'nama_kontak_upi' => [
				'type' 			=> 'text',
				'null' 			=> true
			],
			'foto_pabrik' => [
				'type' 			=> 'text',
				'null' 			=> true
			],
			'kelurahan_desa' => [
				'type' 			=> 'BIGINT',
				'constraint' 	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'kecamatan' => [
				'type' 			=> 'BIGINT',
				'constraint' 	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'kab_kota' => [
				'type' 			=> 'BIGINT',
				'constraint' 	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'provinsi' => [
				'type' 			=> 'BIGINT',
				'constraint' 	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'created_by' => [
				'type' 			=> 'BIGINT',
				'constraint' 	=> 20,
				'unsigned'      => true,
				'null'			=> false
			],
			'is_main' => [
				'type' 			=> 'BOOL',
				'default'		=> false
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
		// $this->forge->addForeignKey('created_by', 'tbl_user', 'id', 'CASCADE', 'CASCADE');
		// $this->forge->addForeignKey('kelurahan_desa', 'tbl_location', 'id', 'CASCADE', 'CASCADE');
		// $this->forge->addForeignKey('kecamatan', 'tbl_location', 'id', 'CASCADE', 'CASCADE');
		// $this->forge->addForeignKey('kab_kota', 'tbl_location', 'id', 'CASCADE', 'CASCADE');
		// $this->forge->addForeignKey('provinsi', 'tbl_location', 'id', 'CASCADE', 'CASCADE');
		$this->forge->createTable('tbl_upi');
	}

	public function down()
	{
		$this->forge->dropTable('tbl_upi');
	}
}
