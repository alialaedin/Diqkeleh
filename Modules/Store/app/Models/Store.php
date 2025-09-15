<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\BaseModel;
use Modules\Product\Models\Product;

class Store extends BaseModel
{
	protected $fillable = ['balance', 'product_id'];
	protected $attributes = ['balance' => 0];

	#[Scope]
	protected function filters(Builder $query): void
	{
		$query
			->when(request()->product_id, fn(Builder $q) => $q->where('id', request()->product_id))
			->when(request()->category_id, function (Builder $q)  {
				$q->whereHas('product', fn($pq) => $pq->where('category_id', request()->category_id));
			})
			->when(request()->start_date, fn(Builder $q) => $q->whereDate('created_at', '>=', request()->start_date))
			->when(request()->end_date, fn(Builder $q) => $q->whereDate('created_at', '<=', request()->end_date))
		;
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	public function transactions(): HasMany
	{
		return $this->hasMany(StoreTransaction::class);
	}
}
