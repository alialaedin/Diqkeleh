<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;

class Address extends BaseModel
{
	protected $fillable = ['customer_id', 'first_name', 'last_name', 'mobile', 'address', 'postal_code'];

	#[Scope]
	protected function filters(Builder $query): void
	{
		$query
			->when(request()->customer_id, function (Builder $q) {
				$q->where('customer_id', '=', request()->customer_id);
			})
			->when(request()->mobile, function (Builder $q) {
				$q->where('mobile', '=', request()->mobile);
			})
			->when(request()->first_name, function (Builder $q) {
				$q->where('first_name', 'LIKE', '%' . request()->first_name . '%');
			})
			->when(request()->last_name, function (Builder $q) {
				$q->where('last_name', 'LIKE', '%' . request()->last_name . '%');
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
}
