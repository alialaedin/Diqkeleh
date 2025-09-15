<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\BaseModel;
use Modules\Customer\Models\Customer;
use Modules\Payment\Enums\PaymentType;

class Payment extends BaseModel
{
	protected $fillable = ['customer_id', 'amount', 'type', 'description', 'paid_at', 'created_at', 'updated_at'];
	protected $casts = ['type' => PaymentType::class];

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
				$q->whereHas('customer', function ($q) use ($customerMobile, $customerName) {
					$q->when($customerName, fn($q) => $q->orWhere('full_name', 'LIKE', '%' . $customerName . '%'));
					$q->when($customerMobile, fn($q) => $q->orWhere('mobile', '=', $customerMobile));
				});
			});
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}
}
