<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Core\Enums\BooleanStatus;
use Modules\Core\Models\BaseModel;
use Modules\Core\Traits\PreventDeletionIfRelationsExist;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Models\Wallet;
use Modules\Wallet\Models\WalletTransaction;

class Customer extends BaseModel
{
	use PreventDeletionIfRelationsExist;

	protected $fillable = ['first_name', 'last_name', 'mobile', 'status'];
	protected $storedFields = ['full_name'];
	protected $with = ['wallet'];
	protected $casts = ['status' => BooleanStatus::class];
	protected $attributes = ['status' => BooleanStatus::TRUE];
	protected $relationsPreventingDeletion = [
		'orders' => 'به دلیل وجود سفارش قابل حذف نمی باشد',
		'payments' => 'به دلیل وجود پرداختی قابل حذف نمی باشد',
	];

	protected static function booted(): void
	{
		static::created(function (self $customer) {
			$customer->wallet()->create([
				'main_balance' => 0,
				'gift_balance' => 0
			]);
		});
	}

	protected function totalSalesAmount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->orders->where('status', '!=', OrderStatus::CANCELED)->sum(fn(Order $o) => $o->total_amount) ?? 0
		);
	}

	protected function totalPaymentAmount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->payments->sum('amount') ?? 0
		);
	}

	protected function remainingAmount(): Attribute
	{
		return Attribute::make(
			get: fn(): int => $this->total_sales_amount - $this->total_payment_amount
		);
	}

	#[Scope]
	protected function filters(Builder $query): void
	{
		$query
			->when(request()->customer_id, function (Builder $q) {
				$q->where('id', '=', request()->customer_id);
			})
			->when(request()->mobile, function (Builder $q) {
				$q->where('mobile', '=', request()->mobile);
			})
			->when(request()->first_name, function (Builder $q) {
				$q->where('first_name', 'LIKE', '%' . request()->first_name . '%');
			})
			->when(request()->last_name, function (Builder $q) {
				$q->where('last_name', 'LIKE', '%' . request()->last_name . '%');
			})
			->when(request()->start_date, function (Builder $q) {
				$q->whereDate('created_at', '>=', request()->start_date);
			})
			->when(request()->end_date, function (Builder $q) {
				$q->whereDate('created_at', '<=', request()->end_date);
			});
	}

	public function wallet(): HasOne
	{
		return $this->hasOne(Wallet::class);
	}

	public function walletTransactions(): HasManyThrough
	{
		return $this->hasManyThrough(WalletTransaction::class, Wallet::class);
	}

	public function deposits(): HasManyThrough
	{
		return $this->walletTransactions()->where('type', WalletTransactionType::DEPOSIT);
	}

	public function withdraws(): HasManyThrough
	{
		return $this->walletTransactions()->where('type', WalletTransactionType::WITHDRAW);
	}

	public function addresses(): HasMany
	{
		return $this->hasMany(Address::class);
	}

	public function payments(): HasMany
	{
		return $this->hasMany(Payment::class);
	}

	public function orders(): HasMany
	{
		return $this->hasMany(Order::class);
	}

	public function activeOrders(): HasMany
	{
		return $this->orders()->where('status', '!=', OrderStatus::CANCELED);
	}
}
