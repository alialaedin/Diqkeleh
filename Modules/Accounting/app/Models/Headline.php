<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Accounting\Enums\HeadlineType;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;

class Headline extends BaseModel
{
	use HasCache, PreventDeletionIfRelationsExist;

	protected $fillable = ['title', 'type', 'status'];
	protected $cacheKeys = ['all_headlines'];
	protected $casts = ['type' => HeadlineType::class];
	protected $relationsPreventingDeletion = [
		'revenues' => 'به دلیل ثبت شدن درآمد ازین سرفصل قابل حذف نمی باشد',
		'expenses' => 'به دلیل ثبت شدن هزینه ازین سرفصل قابل حذف نمی باشد',
	];

	public static function getAll(): Collection
	{
		return Cache::rememberForever('all_headlines', fn() => self::latest()->get());
	}
	
	public static function getAllByType(HeadlineType $type): Collection
	{
		return self::getAll()->filter(fn (Headline $h) => $h->type == $type && $h->status);
	}

	public function expenses(): HasMany
	{
		return $this->hasMany(Expense::class);
	}

	public function revenues(): HasMany
	{
		return $this->hasMany(Revenue::class);
	}
}
