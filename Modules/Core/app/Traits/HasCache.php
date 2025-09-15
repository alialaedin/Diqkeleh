<?php

namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait HasCache
{
  public static function bootHasCache(): void
  {
    foreach (['created', 'updated', 'deleted'] as $event) {
      static::$event(function (Model $model) {
        $model->forgetAllCaches();
      });
    }
  }

  public function forgetAllCaches(): void
  {
    if (empty($this->cacheKeys) || !is_iterable($this->cacheKeys)) {
      return;
    }

    foreach ($this->cacheKeys as $cacheKey) {
      Cache::forget($cacheKey);
    }
  }
}
