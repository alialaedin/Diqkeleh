<?php

namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;

trait PreventDeletionIfRelationsExist
{
  protected static function bootPreventDeletionIfRelationsExist()
  {
    static::deleting(function (Model $model) {

      if (!property_exists($model, 'relationsPreventingDeletion')) {
        return;
      }

      foreach ($model->relationsPreventingDeletion as $relation => $message) {
        if ($model->$relation()->exists()) {
          throw new ModelCannotBeDeletedException($message);
        }
      }
    });
  }
}
