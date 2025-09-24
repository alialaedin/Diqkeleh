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

		$categories = self::getCategoriesForAdmin()
			->whereIn('id', $idsFromRequest)
			->keyBy('id');

		foreach ($idsFromRequest as $index => $id) {
			if (isset($categories[$id])) {
				$category = $categories[$id];
				$category->order = $index + 1;
				$category->save();
			}
		}
	}


	public function sortProducts(Request $request)
	{
		$idsFromRequest = $request->input('products');
		$products = $this->products->whereIn('id', $idsFromRequest)->keyBy('id');

		foreach ($idsFromRequest as $index => $id) {
			if (isset($products[$id])) {
				$product = $products[$id];
				$product->order = $index + 1;
				$product->save();
			}
		}
	}


	public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}
}
