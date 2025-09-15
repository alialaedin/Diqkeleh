<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Enums\BooleanStatus;
use Modules\Core\Models\BaseModel;
use Modules\Product\Models\Product;

class OrderItem extends BaseModel
{
	protected $fillable = ['order_id', 'product_id', 'quantity', 'amount', 'discount_amount', 'status'];
	protected $storedFields = ['total_amount'];
	protected $appends = ['total_base_amount', 'total_discount_amount'];
	protected $casts = ['status' => BooleanStatus::class];

	protected function totalBaseAmount(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->attributes['amount'] * $this->attributes['quantity']
		);
	}

	protected function totalDiscountAmount(): Attribute
	{
		return Attribute::make(
			get: fn() => $this->attributes['discount_amount'] * $this->attributes['quantity']
		);
	}

	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
}
