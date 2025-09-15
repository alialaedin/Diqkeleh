<?php

namespace Modules\Unit\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasAuthors;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;
use Modules\Product\Models\Product;

class Unit extends BaseModel
{
	use HasCache, HasAuthors, PreventDeletionIfRelationsExist;

	protected $fillable = ['name', 'label', 'status'];
	protected $withCount = ['products'];
	protected $cacheKeys = ['all_units'];
	protected $relationsPreventingDeletion = [
		'products' => 'این واحد به محصولی متصل است و قابل حذف نمی باشد'
	];

	public static function getAll(bool $onlyActives = false): Collection
	{
		$units = Cache::rememberForever(
			'all_units',
			fn(): Collection => self::with(['creator', 'updater'])->latest()->get()
		);

		if ($onlyActives) {
			$units = $units->filter(fn (self $u) => $u->status == 1);
		}

		return $units;
	}

	public function products()
	{
		return $this->hasMany(Product::class);
	}
}
