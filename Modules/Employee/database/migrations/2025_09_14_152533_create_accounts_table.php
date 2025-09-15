<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Employee\Models\Employee;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('accounts', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();
			$table->string('bank_name', 50);
			$table->string('card_number', 20);
			$table->string('sheba_number', 30);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('accounts');
	}
};
