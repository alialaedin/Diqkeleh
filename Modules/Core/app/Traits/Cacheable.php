<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
	public static function bootCacheable(): void
	{
		static::created(fn() => static::forgetAllCaches());
		static::updated(fn() => static::forgetAllCaches());
		static::deleted(fn() => static::forgetAllCaches());
	}

	public static function forgetAllCaches(): void
	{
		foreach (static::getCacheKeys() as $cacheKey) {
			Cache::forget($cacheKey);
		}
	}

	/**
	 * Get an array of cache keys that should be cleared on model changes.
	 *
	 * @return array<string>
	 */
	abstract protected static function getCacheKeys(): array;
}
