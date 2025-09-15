<?php

namespace Modules\Area\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasActivityLog;

class Province extends Model
{
	use HasActivityLog;
	protected $fillable = ['name', 'status'];

	public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(City::class);
	}

	// public function shippings()
	// {
	// 	return $this->morphToMany(Shipping::class, 'shippable');
	// }
}
