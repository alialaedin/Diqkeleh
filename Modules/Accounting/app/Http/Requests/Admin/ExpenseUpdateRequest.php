<?php

namespace Modules\Accounting\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class ExpenseUpdateRequest extends FormRequest
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
			'headline_id' => ['required', 'integer', 'exists:headlines,id'],
			'title' => ['required', 'string', 'min:3', 'max:190'],
			'amount' => ['required', 'integer', 'min:1000', 'max:9999999999'],
			'payment_date' => ['required', 'date'],
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
