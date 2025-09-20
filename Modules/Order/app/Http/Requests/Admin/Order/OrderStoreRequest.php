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
				'nullable',
				'integer',
				Rule::requiredIf(fn () => !$this->boolean('is_in_person')),
				Rule::exists('addresses', 'id')->where(function ($query) {
					return $query->where('customer_id', $this->customer_id);
				})
			],
			'is_in_person' => ['required', 'boolean'],
			'full_name' => ['required', 'string', 'min:3', 'max:190'],
			'discount_amount' => ['required', 'integer', 'min:0'],
			'shipping_amount' => ['required', 'integer', 'min:0'],
			'from_wallet_amount' => ['required', 'integer', 'min:0'],
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
