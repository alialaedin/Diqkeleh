<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Modules\Category\Models\Category;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\OrderItem;
use Modules\Product\Enums\ProductDiscountType;
use Modules\Product\Enums\ProductStatus;
use Modules\Store\Models\Store;
use Modules\Unit\Models\Unit;

class Product extends BaseModel
{
	use HasCache, PreventDeletionIfRelationsExist;

	protected $fillable = [
		'title',
		'category_id',
		'unit_id',
		'status',
		'unit_price',
		'discount',
		'discount_type',
		'discount_until',
		'has_daily_balance',
		'order'
	];

	protected $appends = ['final_price', 'discount_amount'];
	protected $with = ['category'];
	protected $cacheKeys = ['products_for_filter', 'all_products', 'admin_categories'];
	protected $relationsPreventingDeletion = ['orderItems' => 'به دلیل وجود سفارش برای این محصول امکان حذف ندارد'];

	protected $attributes = [
		'status' => ProductStatus::DRAFT,
		'has_daily_balance' => 0
	];

	protected $casts = [
		'status' => ProductStatus::class,
		'discount_type' => ProductDiscountType::class,
	];

	protected static function booted(): void
	{
		static::creating(function (self $product) {
			$maxOrder = self::where('category_id', $product->category_id)->max('order');
			$product->order = ($maxOrder ?? 0) + 1;
		});
	}

	public static function getAllProductsForFilter(): Collection
	{
		return Cache::rememberForever(
			'products_for_filter',
			fn() => self::latest('id')->get(['id', 'category_id', 'title'])
		);
	}

	public static function getAllProducts(): Collection
	{
		return Cache::rememberForever(
			'all_products',
			fn() => self::latest('id')->get()
		);
	}

	protected function discountAmount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->calculateDiscount()
		);
	}

	protected function finalPrice(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->unit_price - $this->discount_amount
		);
	}

	protected function salesAmount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->activeOrderItems?->sum('total_amount') ?? 0
		);
	}

	protected function salesCount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->activeOrderItems?->sum('quantity') ?? 0
		);
	}

	#[Scope]
	protected function available(Builder $query): void
	{
		$query->where('status', ProductStatus::DRAFT);
	}

	#[Scope]
	protected function filters(Builder $query): void
	{
		$query
			->when(request()->product_id, fn(Builder $q) => $q->where('id', request()->product_id))
			->when(request()->category_id, fn(Builder $q) => $q->where('category_id', request()->category_id))
			->when(request()->title, fn(Builder $q) => $q->where('title', 'LIKE', '%' . request()->title) . '%')
			->when(request()->start_date, fn(Builder $q) => $q->whereDate('created_at', '>=', request()->start_date))
			->when(request()->end_date, fn(Builder $q) => $q->whereDate('created_at', '<=', request()->end_date))
			->when(request()->status, fn(Builder $q) => $q->where('status', request()->status))
		;
	}

	public function isDeletable(): bool
	{
		return $this->orderItems->isEmpty();
	}

	private function calculateDiscount()
	{
		if (!$this->discount_type || !$this->discount || !$this->discount_until) {
			return 0;
		}

		if ($this->discount_until < now()->format('Y-m-d H:i:s')) {
			return 0;
		}

		return $this->discount_type === ProductDiscountType::PERCENTAGE
			? $this->unit_price / $this->dsicount
			: $this->unit_price - $this->dsicount;
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	public function unit(): BelongsTo
	{
		return $this->belongsTo(Unit::class);
	}

	public function store(): HasOne
	{
		return $this->hasOne(Store::class);
	}

	public function orderItems(): HasMany
	{
		return $this->hasMany(OrderItem::class);
	}

	public function activeOrderItems(): HasMany
	{
		return $this->orderItems()
			->whereHas('order', fn($o) => $o->where('status', '!=', OrderStatus::CANCELED))
			->where('status', '=', 1);
	}
}
