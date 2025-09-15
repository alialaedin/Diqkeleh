<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Courier\Enums\CourierType;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('couriers', function (Blueprint $table) {
			$table->id();
			$table->string('first_name', 100);
			$table->string('last_name', 100);
			$table->string('full_name')->storedAs("CONCAT(first_name, ' ', last_name)");
			$table->string('mobile', 20)->unique();
			$table->string('telephone', 191)->nullable();
			$table->string('national_code', 20)->unique();
			$table->text('address');
			$table->enum('type', CourierType::cases());
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('couriers');
	}
};
