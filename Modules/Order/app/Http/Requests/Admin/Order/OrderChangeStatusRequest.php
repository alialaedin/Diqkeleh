<?php

namespace Modules\Order\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Exceptions\ValidationException;
use Modules\Order\Enums\OrderStatus;

class OrderChangeStatusRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'status' => ['required', 'string', Rule::enum(OrderStatus::class)]
		];
	}

	protected function passedValidation()
	{
		$order = $this->route('order');
		if ($order->status == $this->status) {
			throw new ValidationException('وضعیت انتخابی برابر با وضعیت فعلی سفارش است');
		}
	}

	public function authorize(): bool
	{
		return true;
	}
}
