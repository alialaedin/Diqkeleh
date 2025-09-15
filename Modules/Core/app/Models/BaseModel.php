<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
  protected $perPage = 50;

  #[Scope]
  protected function paginateOrAll(Builder $query, ?int $perPage = null, array $columns = ['*'])
  {
    return request()->has('all') ?
      $query->get($columns) :
      $query->paginate($perPage ?? $this->perPage, $columns)->withQueryString();
  }

  #[Scope]
  protected function active(Builder $query, string $column = 'status'): void
  {
    $query->where($column, '=', 1);
  }

  #[Scope]
  protected function filterByStatus(Builder $query, string $column = 'status'): void
  {
    $hasStatus = in_array(request()->status, ['1', '0']);
    $query->when($hasStatus, function ($q) use ($column) {
      $q->where($column, '=', request()->status);
    });
  }

  public function isDeletable(): bool
  {
    return false;
  }
}
