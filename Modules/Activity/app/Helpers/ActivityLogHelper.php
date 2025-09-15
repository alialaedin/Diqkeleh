<?php

namespace Modules\Activity\Helpers;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Activity\Enums\EventType;
use Modules\Admin\Models\Admin;

class ActivityLogHelper
{
	private Authenticatable $admin;
	private Model $model;
	private ?string $description;

	public function __construct(
		private readonly Model $modelInstance,
		private readonly ?string $customDescription = null
	) {
		$this->admin = auth(Admin::GUARD_NAME)->user();
		$this->model = $modelInstance;
		$this->description = $customDescription;
	}

	public function created(): void
	{
		$this->log(EventType::CREATE);
	}

	public function updated(): void
	{
		$changes = $this->model->getChanges();
		unset($changes['updated_at']);

		if (empty($changes)) {
			return;
		}

		$this->log(EventType::UPDATE, ['changedColumns' => $changes]);
	}

	public function deleted(): void
	{
		$original = $this->model->getOriginal();
		$this->log(EventType::DELETE, ['originalColumns' => $original]);
	}

	private function log(EventType $event, array $properties = []): void
	{
		activity()
			->causedBy($this->admin->id)
			->event($event->value)
			->performedOn($this->model)
			->when(!empty($properties), fn($log) => $log->withProperties($properties))
			->log($this->resolveDescription($event));
	}

	private function resolveDescription(EventType $event): string
	{
		if ($this->description) {
			return $this->description;
		}

		$modelClass = get_class($this->model);
		$modelName = config('models.' . $modelClass, class_basename($modelClass));

		return match ($event) {
			EventType::CREATE => "{$modelName} ایجاد شد",
			EventType::UPDATE => "{$modelName} بروز شد",
			EventType::DELETE => "{$modelName} حذف شد",
			default => 'فعالیت انجام شد',
		};
	}
}
