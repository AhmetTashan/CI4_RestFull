<?php namespace App\Database\Seeds;

class User extends \CodeIgniter\Database\Seeder
{
	public function run()
	{
		$templates = [
			[
				'username' => 'atashan',
				'password' => password_hash("password", PASSWORD_DEFAULT),
				'fullname' => 'Ahmet Taşhan',
				'email'    => 'ahmet@tashan.cc',
				'status'   => 'ACTIVE'
			],
			[
				'username' => 'ahmet',
				'password' => password_hash("password", PASSWORD_DEFAULT),
				'fullname' => 'Ahmet Taşhan',
				'email'    => 'ahmet1@tashan.cc',
				'status'   => 'NOTACTIVE'
			],
			[
				'username' => 'tashan',
				'password' => password_hash("password", PASSWORD_DEFAULT),
				'fullname' => 'Ahmet Taşhan',
				'email'    => 'ahmet2@tashan.cc',
				'status'   => 'FORCEPASSRESET'
			],
			[
				'username' => 'ahmettashan',
				'password' => password_hash("password", PASSWORD_DEFAULT),
				'fullname' => 'Ahmet Taşhan',
				'email'    => 'ahmet3@tashan.cc',
				'status'   => 'BANNED'
			]
		];
		
		foreach ($templates as $template) {
			$this->db->table('users')->insert($template);
		}
		
	}
}