<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Exceptions\ValidationException;
use Modules\Core\Models\BaseModel;
use Modules\Customer\Models\Customer;
use Modules\Wallet\Enums\WalletTransactionType;

class Wallet extends BaseModel
{
	protected $fillable = ['customer_id', 'main_balance', 'gift_balance'];
	protected $storedFields = ['balance']; // main_balance + gift_balance

	protected $attributes = [
		'main_balance' => 0,
		'gift_balance' => 0
	];

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function transactions(): HasMany
	{
		return $this->hasMany(WalletTransaction::class, 'wallet_id');
	}
}
