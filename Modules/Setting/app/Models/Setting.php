<?php

namespace Modules\Setting\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;
use Modules\Setting\Enums\SettingType;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends BaseModel implements HasMedia
{
	use HasCache, InteractsWithMedia;

	const SETTINGS_CACHE_KEY = 'settings';
	const MEDIA_COLLECTION_NAME = 'setting_files';

	protected $fillable = ['label', 'name', 'type', 'value', 'options'];
	protected $with = ['media'];
	protected $hidden = ['media'];
	protected $appends = ['file'];
	protected $casts = ['type' => SettingType::class];
	protected $cacheKeys = [self::SETTINGS_CACHE_KEY];

	public static function getAllSettings(): Collection
	{
		return Cache::rememberForever(self::SETTINGS_CACHE_KEY, callback: fn() => self::all());
	}

	public static function getFromName($name)
	{
		return self::getAllSettings()->where('name', $name)->first()?->value ?? null;
	}

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection(self::MEDIA_COLLECTION_NAME)->singleFile();
	}

	public function addFile(UploadedFile $file): Media
	{
		return $this->addMedia($file)->toMediaCollection(self::MEDIA_COLLECTION_NAME);
	}

	public function uploadFile(UploadedFile $file): void
	{
		$this->addFile($file);
	}

	protected function file(): Attribute
	{
		return Attribute::make(get: function () {
			$media = $this->getFirstMedia(self::MEDIA_COLLECTION_NAME);

			return [
				'id' => $media?->id,
				'url' => $media?->getFullUrl(),
				'name' => $media?->file_name
			];
		});
	}
}
