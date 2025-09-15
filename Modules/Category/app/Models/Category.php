<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;
use Modules\Product\Models\Product;

class Category extends BaseModel
{
	use HasCache, PreventDeletionIfRelationsExist;

	protected $fillable = ['title', 'en_title', 'description', 'status'];
	protected $withCount = ['products'];
	protected $cacheKeys = ['admin_categories'];
	protected $relationsPreventingDeletion = ['products' => 'دسته بندی دارای محصول است و قابل حذف نمی باشد'];

	public static function getCategoriesForAdmin(): Collection
	{
		return Cache::rememberForever('admin_categories', fn() => self::latest()->get());
	}

	public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}
}
