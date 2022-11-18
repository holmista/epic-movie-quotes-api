<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		User::factory(2)->create();
		$movies = Movie::factory(5, ['user_id'=>User::all()->random()->id])->hasCategories(2)->hasQuotes(2, ['user_id'=>User::all()->random()->id])->create();
		$movies->each(function ($movie) {
			$movie->quotes->each(function ($quote) {
				$quote->comments()->saveMany(\App\Models\Comment::factory(2)->make(['user_id' => User::all()->random()->id]));
			});
		});
	}
}
