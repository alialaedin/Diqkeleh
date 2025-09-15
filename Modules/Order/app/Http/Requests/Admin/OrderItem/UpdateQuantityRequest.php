<?php

namespace Modules\Order\Http\Requests\Admin\OrderItem;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Enums\BooleanStatus;
use Modules\Core\Exceptions\ValidationException;

class UpdateQuantityRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'quantity' => ['required', 'integer', 'min:1']
		];
	}


	protected function passedValidation()
	{
		$orderItem = $this->route('order_item');

		if ($orderItem->status == BooleanStatus::FALSE) {
			throw new ValidationException('آیتم با وضعیت غیر فعال غیر قابل ویرایش است');
		}

		if ($orderItem->quantity == $this->quantity) {
			throw new ValidationException('تعداد وارد شده برابر با تعداد فعلی محصول در سفارش می باشد');
		}

		$productBalance = $orderItem->product->store->balance;
		$requestQuantity = $this->quantity;
		$currentQuantity = $orderItem->quantity;

		if ($requestQuantity > $currentQuantity && $productBalance < $requestQuantity - $currentQuantity) {
			throw new ValidationException("تعداد سفارش این محصول بیشتر از موجودی است. موجودی این محصول : {$productBalance}");
		}
	}

	public function authorize(): bool
	{
		return true;
	}
}
