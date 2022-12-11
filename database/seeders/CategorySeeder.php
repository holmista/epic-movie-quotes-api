<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$categories = [
			['name' => json_encode(['en'=>'Comedy', 'ka'=>'კომედია'])],
			['name' => json_encode(['en'=>'Action', 'ka'=>'ექშენი'])],
			['name' => json_encode(['en'=>'Drama', 'ka'=>'დრამა'])],
			['name' => json_encode(['en'=>'Animation', 'ka'=>'ანიმაცია'])],
			['name' => json_encode(['en'=>'Mystery', 'ka'=>'მისთერი'])],
			['name' => json_encode(['en'=>'Thriller', 'ka'=>'ტრილერი'])],
		];
		DB::table('categories')->insert($categories);
	}
}
