<?php

namespace Modules\Courier\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;
use Modules\Courier\Enums\CourierType;
use Modules\Order\Models\Order;

class Courier extends BaseModel
{
	use HasCache, PreventDeletionIfRelationsExist;

	protected $fillable = ['first_name', 'last_name', 'mobile', 'national_code', 'telephone', 'address', 'type'];
	protected $storedFields = ['full_name'];
	protected $casts = ['type' => CourierType::class];
	protected $cacheKeys = ['all_couriers'];
	protected $relationsPreventingDeletion = ['orders' => 'به دلیل وجود پیک در سفارشاتی قابل حذف نمی باشد'];

	public static function getAll(): Collection
	{
		return Cache::rememberForever('all_couriers', fn() => self::latest()->get());
	}

	public function orders(): HasMany
	{
		return $this->hasMany(Order::class);
	}
}
