<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Exceptions\ValidationException;
use Modules\Core\Traits\HasCache;
use Modules\Permission\Models\Role;
use Modules\Store\Models\StoreTransaction;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
	use HasRoles, HasCache;

	public const GUARD_NAME = 'admin';

	protected $fillable = ['name', 'username', 'email', 'password', 'mobile', 'status', 'remember_token'];
	protected $appends = ['role'];
	protected $hidden = ['roles', 'password', 'remember_token'];
	protected $cacheKeys = ['all_admins'];

	protected static function booted()
	{
		parent::booted();
		static::updating(function (self $admin) {
			$user = Auth::guard(self::GUARD_NAME)->user();
			if ($user && !$user->isSuperAdmin() && $admin->isSuperAdmin()) {
				throw new ValidationException('شما مجاز به ویرایش سوپر ادمین نمیباشید');
			}
		});
	}

	public static function storeOrUpdate(Request $request, ?self $admin = null)
	{
		$attributes = $request->only(['name', 'username', 'mobile']);

		if ($request->filled('password')) {
			$attributes['password'] = Hash::make($request->input('password'));
		}

		if ($admin) {
			$admin->update($attributes);
		} else {
			$admin = self::create($attributes);
		}

		$role = Role::findById($request->role_id);
		$permissions = $role->permissions;

		$admin->syncRoles($role);
		$admin->refresh();
		$admin->syncPermissions($permissions);

		return $admin;
	}

	public static function getAll(): Collection
	{
		return Cache::rememberForever('all_admins', fn() => self::latest()->get());
	}

	public function isSuperAdmin(): bool
	{
		return $this->hasRole(Role::SUPER_ADMIN_ROLE, self::GUARD_NAME);
	}

	protected function password(): Attribute
	{
		return Attribute::make(
			set: fn($pass) => is_null($pass) ? bcrypt(123456) : bcrypt($pass)
		);
	}

	protected function role(): Attribute
	{
		return Attribute::make(
			get: fn() => empty($this->roles) ? null : $this->roles()->first()
		);
	}

	protected function activities(): Attribute
	{
		return Attribute::make(
			get: function () {
				return Activity::query()
					->select('id', 'causer_id', 'description', 'created_at')
					->where('causer_id', $this->id)
					->latest('id')
					->paginate(100);
			}
		);
	}

	public function storeTransactions(): MorphMany
	{
		return $this->morphMany(StoreTransaction::class, 'creatorable');
	}
}
