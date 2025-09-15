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
		Schema::create('wallets', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
			$table->unsignedBigInteger('main_balance');
			$table->unsignedBigInteger('gift_balance');
			$table->unsignedBigInteger('balance')->storedAs('main_balance + gift_balance');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('wallets');
	}
};
