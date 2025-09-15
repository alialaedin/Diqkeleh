<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;

class Employee extends BaseModel
{
	use HasCache, PreventDeletionIfRelationsExist;

	protected $fillable = ['full_name', 'mobile', 'address', 'national_code', 'employmented_at', 'base_salary', 'telephone'];
	protected $cacheKeys = ['all_employees'];
	protected $withCount = ['salaries', 'accounts'];
	protected $relationsPreventingDeletion = [
		'salaries' => 'به دلیل پرداخت حقوق به کارمند امکان حذف آن وجود ندارد',
	];

	public static function getAll()
	{
		return Cache::rememberForever(
			'all_employees',
			fn() => self::latest()->get()
		);
	}

	public function salaries(): HasMany
	{
		return $this->hasMany(Salary::class);
	}

	public function accounts(): HasMany
	{
		return $this->hasMany(Account::class);
	}
}
