<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AccessToken extends Migration
{
	public function up()
	{
		/**
		 * @table_name Access Token
		 */
		$this->forge->addField([
			'id'               => ['type' => 'bigint', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
			'access_token'     => ['type' => 'varchar', 'constraint' => 64, 'null' => false],
			'user_id'          => ['type' => 'bigint', 'constraint' => 20],
			'expires TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()'
		]);
		
		
		$this->forge->addKey('id', true);
		$this->forge->addUniqueKey('access_token');
		
		$this->forge->createTable('access_token', true);
	}
	
	//--------------------------------------------------------------------
	
	public function down()
	{
		$this->forge->dropTable('access_token', true);
	}
}
