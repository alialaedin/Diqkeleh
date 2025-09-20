<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Exceptions\ModelCannotBeUpdatedException;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;

class Range extends BaseModel
{
	use HasCache, PreventDeletionIfRelationsExist;

	protected $fillable = ['title', 'status', 'shipping_amount'];
	protected $cacheKeys = ['all_ranges'];
	protected $relationsPreventingDeletion = [
		'addresses' => 'این محدوده به آدرسی نسبت داده شده است'
	];

	protected static function booted()
	{
		static::updating(function (self $range) {
			if (
				$range->isDirty('status') &&
				$range->addresses()->exists() &&
				$range->getAttribute('status') == 0
			) {
				throw new ModelCannotBeUpdatedException(
					'این محدوده به آدرسی نسبت داده شده است و وضعیت آن نمی تواند غیرفعال باشد'
				);
			}
		});
	}

	public static function getAll(bool $onlyActives = false)
	{
		$allRanges = Cache::rememberForever('all_ranges', fn(): Collection => self::latest()->get());
		$allRanges = $allRanges->when(
			$onlyActives,
			fn($c) => $c->filter(fn(self $r) => $r->status == 1)
		);

		return $allRanges;
	}

	public function addresses()
	{
		return $this->hasMany(Address::class);
	}
}
