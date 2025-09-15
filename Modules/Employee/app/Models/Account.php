<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\HasCache;

class Account extends BaseModel
{
	use HasCache;

	protected $fillable = ['employee_id', 'bank_name', 'card_number', 'sheba_number'];
	protected $with = ['employee'];
	protected $cacheKeys = ['all_accounts'];

	public static function getAll()
	{
		return Cache::rememberForever(
			'all_accounts',
			fn() => self::latest()->get()
		);
	}

	public function employee(): BelongsTo
	{
		return $this->belongsTo(Employee::class);
	}
}
