<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Enums\BooleanStatus;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('order_items', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
			$table->unsignedInteger('quantity');
			$table->unsignedBigInteger('amount');
			$table->unsignedBigInteger('discount_amount')->default(0);
			$table->unsignedBigInteger('total_amount')->storedAs("(amount - discount_amount) * quantity");
			$table->boolean('status')->default(BooleanStatus::TRUE);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('order_items');
	}
};
