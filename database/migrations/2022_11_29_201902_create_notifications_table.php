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
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
			$table->foreignId('trigerer_id')->constrained('users')->onDelete('cascade');
			$table->foreignId('quote_id')->constrained('quotes')->onDelete('cascade');
			$table->tinyInteger('type');
			$table->boolean('is_read');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('notifications');
	}
};
