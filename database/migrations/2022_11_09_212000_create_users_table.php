<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->unsignedBigInteger('email_id');
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password')->nullable();
			$table->string('google_id')->nullable();
			$table->rememberToken();
			$table->timestamps();
			$table->foreign('email_id')->references('id')->on('emails');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
};
