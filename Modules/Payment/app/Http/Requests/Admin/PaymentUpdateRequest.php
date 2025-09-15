<?php

namespace Modules\Payment\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;
use Modules\Payment\Enums\PaymentType;

class PaymentUpdateRequest extends FormRequest
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
			'type' => ['required', Rule::enum(PaymentType::class)],
			'amount' => ['required', 'integer', 'min:1000'],
			'paid_at' => ['nullable', 'date'],
			'description' => ['nullable', 'string']
		];
	}
	public function authorize(): bool
	{
		return true;
	}
}
