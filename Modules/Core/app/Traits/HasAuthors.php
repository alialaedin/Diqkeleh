<?php

namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Admin;

trait HasAuthors
{
  public static function bootHasAuthors(): void
  {
    $user = Auth::guard(Admin::GUARD_NAME)->user();
    
    if ($user && $user instanceof Admin) {

      static::creating(function (Model $model) use ($user): void {
        $model->creator()->associate($user);
        $model->updater()->associate($user);
      });

      static::updating(function (Model $model) use ($user): void {
        $model->updater()->associate($user);
      });
    }
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(Admin::class, 'creator_id');
  }

  public function updater(): BelongsTo
  {
    return $this->belongsTo(Admin::class, 'updater_id');
  }
}
