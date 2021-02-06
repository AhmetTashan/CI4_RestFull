<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
		/**
		 * @tablo_name User
		 */
		$this->forge->addField([
			'id' => ['type' => 'bigint', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
			'created_at DATETIME NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()',
			'username' => ['type' => 'varchar', 'constraint' => 64],
			'password' => ['type' => 'varchar', 'constraint' => 128],
			'fullname' => ['type' => 'varchar', 'constraint' => 64],
			'email' => ['type' => 'varchar', 'constraint' => 128],
			'activate_hash' => ['type' => 'varchar', 'constraint' => 64, 'null' => true],
			'reset_hash' => ['type' => 'varchar', 'constraint' => 64, 'null' => true],
			'status' => ['type' => 'enum("ACTIVE","NOTACTIVE", "FORCEPASSRESET","BANNED")', 'default' => 'NOTACTIVE'],
			'updated_at' => ['type' => 'datetime', 'null' => true],
			'deleted_at' => ['type' => 'datetime', 'null' => true],
		]);
		
		$this->forge->addKey('id', true);
		$this->forge->addUniqueKey('login');
		$this->forge->addUniqueKey('email');
		
		$this->forge->createTable('users', true);
	}
	
	//--------------------------------------------------------------------
	
	public function down()
	{
		$this->forge->dropTable('users', true);
	}
}
