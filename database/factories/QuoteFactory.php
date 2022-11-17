<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		$faker = FakerFactory::create('ka_GE');
		return [
			'title'        => json_encode(['en' => fake()->name, 'ka'=> $faker->name]),
			'avatar'       => fake()->imageUrl(),
		];
	}
}
