<?php

namespace Modules\Employee\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;
use Modules\Core\Rules\IranMobile;

class StoreRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		if ($this->filled('base_salary')) {
			$this->merge([
				'base_salary' => Helpers::removeComma($this->base_salary)
			]);
		}
	}

	public function rules(): array
	{
		return [
			'full_name' => ['required', 'string', 'min:3', 'max:60'],
			'mobile' => ['required', 'numeric', 'unique:employees,mobile', 'digits:11', new IranMobile()],
			'telephone' => ['nullable', 'numeric', 'digits:11'],
			'address' => ['required', 'string'],
			'national_code' => ['nullable', 'string', 'numeric', 'digits:10', 'unique:employees,national_code'],
			'employmented_at' => ['required', 'date'],
			'base_salary' => ['required', 'integer', 'min:1000'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
