<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Courier\Models\Courier;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Order\Enums\OrderStatus;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Courier::class)->nullable()->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Address::class)->nullable()->constrained()->cascadeOnDelete();
			$table->unsignedInteger('shipping_amount')->default(0);
			$table->unsignedInteger('discount_amount')->default(0);
			$table->enum('status', OrderStatus::cases())->default(OrderStatus::NEW);
			$table->text('description')->nullable();
			$table->json('address')->nullable();
			$table->boolean('is_settled')->default(0);
			$table->timestamp('delivered_at')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('orders');
	}
};
