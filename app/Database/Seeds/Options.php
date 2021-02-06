<?php namespace App\Database\Seeds;

class Options extends \CodeIgniter\Database\Seeder
{
	public function run()
	{
		$templates = [
			[
				'name' => 'home',
				'value' => 'http://localhost:8080/'
			],
			[
				'name' => 'name',
				'value' => 'Proje Adı'
			],
			[
				'name' => 'description',
				'value' => 'Proje slogan'
			]
		];
		
		foreach ($templates as $template) {
			$this->db->table('options')->insert($template);
		}
		
	}
}