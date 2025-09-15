<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasMorphAuthors;
use Modules\Store\Enums\StoreType;

class StoreTransaction extends BaseModel
{
	use HasMorphAuthors;
	protected $fillable = ['store_id', 'type', 'description', 'quantity'];
	protected $hidden = ['creatorable', 'updaterable'];
	protected $casts = ['type' => StoreType::class];

	#[Scope]
	protected function filters(Builder $query): void
	{
		$query
			->when(request()->product_id, fn(Builder $q) => $q->where('id', request()->product_id))
			->when(request()->type, fn(Builder $q) => $q->where('type', request()->type))
			->when(request()->start_date, fn(Builder $q) => $q->whereDate('created_at', '>=', request()->start_date))
			->when(request()->end_date, fn(Builder $q) => $q->whereDate('created_at', '<=', request()->end_date))
		;
	}

	public function store(): BelongsTo
	{
		return $this->belongsTo(Store::class);
	}
}
