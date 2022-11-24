<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('category_movie', function (Blueprint $table) {
			$table->id();
			$table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
			$table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
			$table->unique(['movie_id', 'category_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('movies_categories');
	}
};