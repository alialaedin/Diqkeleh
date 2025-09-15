<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Wallet\Enums\WalletTransactionType;

class WalletTransaction extends BaseModel
{
	protected $fillable = ['wallet_id', 'type', 'amount', 'description'];
	protected $casts = ['type' => WalletTransactionType::class];

	#[Scope]
	protected function filters($query)
	{
		$type = request()->type;
		$startDate = request()->start_date;
		$endDate = request()->end_date;
		$customerName = request()->customer_name;
		$customerMobile = request()->customer_mobile;

		$query
			->when($type, fn($q) => $q->where('type', $type))
			->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
			->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
			->when($customerName || $customerMobile, function ($q) use ($customerName, $customerMobile) {
				$q->whereHas('wallet.customer', function ($q) use ($customerMobile, $customerName) {
					$q->when($customerName, fn($q) => $q->orWhere('full_name', 'LIKE', '%' . $customerName . '%'));
					$q->when($customerMobile, fn($q) => $q->orWhere('mobile', '=', $customerMobile));
				});
			});
	}

	public function wallet(): BelongsTo
	{
		return $this->belongsTo(Wallet::class);
	}
}
