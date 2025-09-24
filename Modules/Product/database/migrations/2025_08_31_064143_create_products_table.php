<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Category\Models\Category;
use Modules\Product\Enums\ProductDiscountType;
use Modules\Product\Enums\ProductStatus;
use Modules\Unit\Models\Unit;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->string('title')->unique();
			$table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Unit::class)->constrained()->cascadeOnDelete();
			$table->enum('status', ProductStatus::cases());
			$table->unsignedBigInteger('unit_price');
			$table->unsignedInteger('discount')->nullable();
			$table->enum('discount_type', ProductDiscountType::cases())->nullable();
			$table->timestamp('discount_until')->nullable();
			$table->boolean('has_daily_balance')->default(0);
			$table->unsignedInteger('order');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('products');
	}
};
