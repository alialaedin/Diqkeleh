<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('categories', function (Blueprint $table) {
			$table->id();
			$table->string('title')->unique();
			$table->string('en_title')->unique();
			$table->text('description')->nullable();
			$table->boolean('status')->default(1);
			$table->unsignedInteger('order');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('categories');
	}
};
