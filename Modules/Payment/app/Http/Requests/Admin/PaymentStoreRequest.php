<?php

namespace Modules\Payment\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;
use Modules\Payment\Enums\PaymentType;

class PaymentStoreRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		if ($this->filled('amount')) {
			$this->merge([
				'amount' => Helpers::removeComma($this->amount)
			]);
		}
	}
	
	public function rules(): array
	{
		return [
			'customer_id' => ['bail', 'required', 'integer', 'exists:customers,id'],
			'type' => ['required', Rule::enum(PaymentType::class)],
			'amount'=> ['required', 'integer', 'min:1000'],
			'paid_at' => ['nullable', 'date'],
			'description' => ['nullable', 'string']
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
