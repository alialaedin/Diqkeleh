<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;
use Modules\Product\Models\Product;

class Category extends BaseModel
{
	use HasCache, PreventDeletionIfRelationsExist;

	protected $fillable = ['title', 'en_title', 'description', 'status', 'order'];
	protected $withCount = ['products'];
	protected $cacheKeys = ['admin_categories'];
	protected $relationsPreventingDeletion = ['products' => 'دسته بندی دارای محصول است و قابل حذف نمی باشد'];

	protected static function booted(): void
	{
		static::creating(function ($category) {
			$category->order = self::query()->max('order') + 1;
		});
	}

	public static function getCategoriesForAdmin(): Collection
	{
		return Cache::rememberForever('admin_categories', fn() => self::query()->orderBy('order')->get());
	}

	public static function sort(Request $request)
	{
		$idsFromRequest = $request->input('orders');
		$c = 1;
		foreach ($idsFromRequest as $id) {
			$cat = self::getCategoriesForAdmin()->where('id', $id)->first();
			$cat->order = $c++;
			$cat->save();
		}
	}

	public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}
}
