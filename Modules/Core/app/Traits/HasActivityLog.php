<?php

namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\Admin;

trait HasActivityLog
{
	private const EVENT_UPDATE = 'update';
	private const EVENT_CREATE = 'create';
	private const EVENT_DELETE = 'delete';

	public static function bootHasActivityLog(): void
	{
		static::created(fn(Model $model) => self::logCreated($model));
		static::updated(fn(Model $model) => self::logUpdated($model));
		static::deleted(fn(Model $model) => self::logDeleted($model));
	}

	private static function logCreated(Model $model): void
	{
		activity()
			->causedBy(auth(Admin::GUARD_NAME)->id())
			->event(self::EVENT_CREATE)
			->performedOn($model)
			->log(self::getLogDescription(self::EVENT_CREATE, $model));
	}

	private static function logUpdated(Model $model): void
	{
		$changedColumns = $model->getChanges();
		unset($changedColumns['updated_at']);

		if (empty($changedColumns)) {
			return;
		}

		activity()
			->causedBy(auth(Admin::GUARD_NAME)->id())
			->event(self::EVENT_UPDATE)
			->performedOn($model)
			->withProperties(['changedColumns' => $changedColumns])
			->log(self::getLogDescription(self::EVENT_UPDATE, $model));
	}

	private static function logDeleted(Model $model): void
	{
		activity()
			->causedBy(auth(Admin::GUARD_NAME)->id())
			->event(self::EVENT_DELETE)
			->performedOn($model)
			->withProperties(['originalColumns' => $model->getOriginal()])
			->log(self::getLogDescription(self::EVENT_DELETE, $model));
	}

	/**
	 * Get the log description based on event and model.
	 *
	 * @param string $event
	 * @param Model $model
	 * @return string
	 */
	private static function getLogDescription(string $event, Model $model): string
	{
		$modelClass = get_class($model);
		$modelName = config('models.' . $modelClass, class_basename($modelClass));

		return match ($event) {
			self::EVENT_CREATE => $modelName . ' ایجاد شد',
			self::EVENT_UPDATE => $modelName . ' بروز شد',
			self::EVENT_DELETE => $modelName . ' حذف شد',
			default => 'فعالیت انجام شد',
		};
	}
}
