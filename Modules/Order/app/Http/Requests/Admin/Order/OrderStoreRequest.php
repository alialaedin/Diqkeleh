<?php

namespace Modules\Order\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Order\Services\OrderValidationService;

class OrderStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'customer_id' => ['bail', 'required', 'integer', 'exists:customers,id'],
			'address_id' => [
				'bail',
				'required',
				'integer',
				Rule::exists('addresses', 'id')->where(function ($query) {
					return $query->where('customer_id', $this->customer_id);
				})
			],
			'first_name' => ['required', 'string', 'min:3', 'max:190'],
			'last_name' => ['required', 'string', 'min:3', 'max:190'],
			'courier_id' => ['bail', 'nullable', 'integer', 'exists:couriers,id'],
			'discount_amount' => ['required', 'integer', 'min:0'],
			'shipping_amount' => ['required', 'integer', 'min:0'],
			'cash_amount' => ['nullable', 'integer', 'min:0'],
			'card_by_card_amount' => ['nullable', 'integer', 'min:0'],
			'pos_amount' => ['nullable', 'integer', 'min:0'],
			'description' => ['nullable', 'string'],
			'products' => ['required', 'array'],
			'products.*.id' => ['bail', 'required', 'integer', 'exists:products,id'],
			'products.*.quantity' => ['required', 'integer', 'min:1'],
			'products.*.amount' => ['required', 'integer', 'min:1000'],
			'products.*.discount_amount' => ['required', 'integer', 'min:0'],
		];
	}
	
	protected function passedValidation()
	{
		(new OrderValidationService($this))->validate();
	}

	public function authorize(): bool
	{
		return true;
	}
}
