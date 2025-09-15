<?php

namespace Modules\Employee\Http\Requests\Admin\Salary;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class UpdateRequest extends FormRequest
{
	protected function prepareForValidation(): void
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
			'employee_id' => ['required', 'integer', 'exists:employees,id'],
			'amount' => ['required', 'integer', 'min:1000'],
			'overtime' => ['nullable', 'integer', 'min:1'],
			'paid_at' => ['required', 'date'],
			'description' => ['nullable', 'string']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
