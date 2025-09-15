<?php

namespace Modules\Permission\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;
use Modules\Permission\Contracts\Role as ContractsRole;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role as RolePermission;

class Role extends RolePermission implements ContractsRole
{
	use HasCache, PreventDeletionIfRelationsExist;

	const SUPER_ADMIN_ROLE = 'super_admin';
	const CASHIER_ROLE = 'cashier';

	protected $cacheKeys = ['all_roles'];
	protected $attributes = ['guard_name' => Admin::GUARD_NAME];
	protected $relationsPreventingDeletion = [
		'users' => 'نقش به ادمینی نسبت داده شده است و قابل حذف نمی باشد'
	];

	protected static function booted()
	{
		static::deleting(function (self $role) {
			if ($role->attributes['name'] == self::SUPER_ADMIN_ROLE) {
				throw new ModelCannotBeDeletedException('نقش سوپر ادمین قابل حذف نمی باشد');
			}
			if ($role->attributes['name'] == self::CASHIER_ROLE) {
				throw new ModelCannotBeDeletedException('نقش صندوقدار قابل حذف نمی باشد');
			}
		});

		static::deleted(function (self $role) {
			foreach ($role->permissions as $permission) {
				$role->revokePermissionTo($permission);
			}
		});
	}

	public static function customFindOrCreate(string $name, string $label, ?string $guardName = null): ContractsRole
	{
		$guardName = $guardName ?? Guard::getDefaultName(static::class);

		$role = static::where('name', $name)->where('guard_name', $guardName)->first();

		if (! $role) {
			return static::query()->create(['name' => $name, 'label' => $label, 'guard_name' => $guardName]);
		}

		return $role;
	}

	public static function getAllRoles(bool $withoutSuperAdminRole = true)
	{
		$allRoles = Cache::rememberForever(
			'all_roles',
			fn(): Collection => self::where('guard_name', Admin::GUARD_NAME)->latest()->get()
		);

		if ($withoutSuperAdminRole) {
			$allRoles = $allRoles->filter(fn(self $role) => $role->name != self::SUPER_ADMIN_ROLE);
		}

		return $allRoles;
	}

	public function isDeletable(): bool
	{
		return !in_array($this->attributes['name'], [self::SUPER_ADMIN_ROLE, self::CASHIER_ROLE]);
	}
}
