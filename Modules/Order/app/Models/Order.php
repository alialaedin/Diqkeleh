<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\BaseModel;
use Modules\Courier\Models\Courier;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Order\Enums\OrderStatus;

class Order extends BaseModel
{
	protected $fillable = [
		'customer_id',
		'courier_id',
		'address_id',
		'shipping_amount',
		'discount_amount',
		'status',
		'description',
		'address',
		'delivered_at',
		'is_settled'
	];

	protected $with = ['activeItems'];
	protected $hidden = ['activeItems'];
	protected $appends = ['total_amount', 'total_items_amount'];
	protected $casts = ['status' => OrderStatus::class];
	protected $attributes = ['is_settled' => 0];

	public function calculateTotalAmount(): int
	{
		$sumItems = $this->total_items_amount ?? 0;
		$discountAmount = $this->attributes['discount_amount'] ?? 0;
		$shippingAmount = $this->attributes['shipping_amount'] ?? 0;

		return $sumItems + $shippingAmount - $discountAmount;
	}

	public function isNew(): bool
	{
		return $this->attributes['status'] == OrderStatus::NEW->value;
	}

	public function isCanceled(): bool
	{
		return $this->attributes['status'] == OrderStatus::CANCELED->value;
	}

	protected function totalAmount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->calculateTotalAmount()
		);
	}

	protected function totalItemsAmount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->activeItems?->sum('total_amount') ?? 0
		);
	}

	protected function shamsiCreatedAt(): Attribute
	{
		return Attribute::make(
			get: fn(): string => verta($this->attributes['created_at'])->format('Y/m/d')
		);
	}

	#[Scope]
	protected function filters(Builder $query)
	{
		$query
			->when(request()->order_id, fn(Builder $q) => $q->where('order_id', request()->order_id))
			->when(request()->status, fn(Builder $q) => $q->where('status', request()->status))
			->when(request()->customer_name || request()->customer_mobile, function ($q) {
				$q->whereHas('customer', function ($customerQ) {
					$customerQ->when(request()->customer_name, fn($q2) => $q2->where('full_name', 'LIKE', '%' . request()->customer_name . '%'));
					$customerQ->when(request()->customer_mobile, fn($q2) => $q2->where('mobile', request()->customer_mobile));
				});
			})
			->filterByDates();
	}

	#[Scope]
	protected function isNotSettled(Builder $query)
	{
		$query->where('is_settled', '=', 0);
	}

	#[Scope]
	protected function delivered(Builder $query)
	{
		$query->where('status', '=', OrderStatus::DELIVERED);
	}

	public function loadNecessaryRelations()
	{
		$this->load([
			'customer',
			'address',
			'items',
			'items.product:id,title',
		]);
	}

	public function address(): BelongsTo
	{
		return $this->belongsTo(Address::class);
	}

	public function courier(): BelongsTo
	{
		return $this->belongsTo(Courier::class);
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function items(): HasMany
	{
		return $this->hasMany(OrderItem::class);
	}

	public function activeItems(): HasMany
	{
		return $this->items()->where('status', 1);
	}
}
