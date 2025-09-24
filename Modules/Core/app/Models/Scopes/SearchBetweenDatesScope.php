<?php

namespace Modules\Core\Models\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SearchBetweenDatesScope implements Scope
{
	public function apply(Builder $builder, Model $model): void
	{
		$filteredColumn = $model->filteredDateColumn ?? 'created_at';
		$fromDateRequestKey = $model->fromDateRequestKey ?? 'start_date';
		$toDateRequestKey = $model->toDateRequestKey ?? 'end_date';

		$fromDate = request()->input($fromDateRequestKey);
		$toDate = request()->input($toDateRequestKey);

		if ($fromDate && $toDate) {
			$builder->whereBetween($filteredColumn, [
				Carbon::parse($fromDate)->startOfDay(),
				Carbon::parse($toDate)->endOfDay()
			]);
		} elseif ($fromDate) {
			$builder->whereDate($filteredColumn, '>=', Carbon::parse($fromDate)->format('Y-m-d'));
		} elseif ($toDate) {
			$builder->whereDate($filteredColumn, '<=', Carbon::parse($toDate)->format('Y-m-d'));
		}
	}
}
