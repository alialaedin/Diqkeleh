<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Customer\Models\Customer;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('addresses', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
			$table->string('first_name', 100)->nullable();
			$table->string('last_name', 100)->nullable();
			$table->string('mobile', 20);
			$table->text('address');
			$table->string('postal_code', 20)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('addresses');
	}
};
