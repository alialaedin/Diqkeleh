<?php

namespace Modules\Core\Traits;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

trait HasMorphAuthors
{
	private static Authenticatable $user;

	public static function bootHasMorphAuthors()
	{
		static::$user = Auth::user();

		static::creating(function (Model $model) {
			$model->creatorable()->associate(static::$user);
			$model->updaterable()->associate(static::$user);
		});

		static::updating(function (Model $model) {
			$model->updaterable()->associate(static::$user);
		});
	}

	public function creatorable(): MorphTo
	{
		return $this->morphTo('creatorable');
	}

	public function updaterable(): MorphTo
	{
		return $this->morphTo('updaterable');
	}
}
