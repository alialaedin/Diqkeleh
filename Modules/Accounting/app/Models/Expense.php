<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;

class Expense extends BaseModel
{
	protected $fillable = ['headline_id', 'title', 'amount', 'payment_date', 'description'];
	protected $with = ['headline'];

	#[Scope]
	protected function filters($query)
	{
		$query
			->when(request()->headline_id, fn($q) => $q->where('headline_id', request()->headline_id))
			->when(request()->title, fn($q) => $q->where('title', 'like', "%" . request()->title . "%"))
			->when(request()->from_payment_date, fn($q) => $q->whereDate('payment_date', '>=', request()->from_payment_date))
			->when(request()->from_payment_date, fn($q) => $q->whereDate('payment_date', '<=', request()->from_payment_date));
	}

	public function headline(): BelongsTo
	{
		return $this->belongsTo(Headline::class);
	}
}
