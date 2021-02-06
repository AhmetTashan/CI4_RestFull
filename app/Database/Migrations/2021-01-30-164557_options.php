<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Options extends Migration
{
	public function up()
	{
		/**
		 * @table_name Options
		 */
		$this->forge->addField([
			'id'               => ['type' => 'bigint', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
			'name'             => ['type' => 'varchar', 'constraint' => 128],
			'value'            => ['type' => 'longtext', 'null' => true],
			'autoload'         => ['type' => 'enum("YES","NO")', 'default' => "YES", 'null' => false],
		]);
		
		$this->forge->addKey('id', true);
		
		$this->forge->createTable('options', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('options', true);
	}
}
