<?php

namespace Modules\Permission\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Traits\HasCache;
use Modules\Permission\Contracts\Permission as ContractsPermission;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission implements ContractsPermission
{
	use HasCache;

	protected $cacheKeys = ['all_permissions'];

	public static function customFindOrCreate(string $name, string $label, ?string $guardName = null): ContractsPermission
	{
		$guardName = $guardName ?? Guard::getDefaultName(static::class);
		$permission = static::getPermissions(['name' => $name, 'guard_name' => $guardName])->first();

		if (! $permission) {
			return static::query()->create(['name' => $name, 'label' => $label, 'guard_name' => $guardName]);
		}

		return $permission;
	}

	public static function getAll(): Collection
	{
		return Cache::rememberForever('all_permissions', fn(): Collection => self::all());
	}
}
