<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;

class Address extends BaseModel
{
	protected $fillable = ['customer_id', 'range_id', 'mobile', 'address', 'postal_code'];
	protected $with = ['range', 'customer'];

	#[Scope]
	protected function filters(Builder $query): void
	{
		$query
			->when(request()->range_id, function (Builder $q) {
				$q->where('range_id', '=', request()->range_id);
			})
			->when(request()->mobile, function (Builder $q) {
				$q->where('mobile', '=', request()->mobile);
			})
			->when(request()->start_date, function (Builder $q) {
				$q->whereDate('created_at', '>=', request()->start_date);
			})
			->when(request()->end_date, function (Builder $q) {
				$q->whereDate('created_at', '<=', request()->end_date);
			});
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function range(): BelongsTo
	{
		return $this->belongsTo(Range::class);
	}
}
