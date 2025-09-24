<?php

namespace Modules\Core\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait FilterableByDates
{
  #[Scope]
  protected function today(Builder $query, $column = 'created_at')
  {
    return $query->whereDate($column, Carbon::today());
  }
}
