<?php

namespace Modules\Employee\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;
use Modules\Core\Rules\IranMobile;

class UpdateRequest extends FormRequest
{
	private int $employeeId;

	protected function prepareForValidation(): void
	{
		$this->employeeId = Helpers::getModelIdFromUrl('employee');

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
			'mobile' => [
				'required',
				'numeric',
				'digits:11',
				Rule::unique('employees', 'mobile')->ignore(id: $this->employeeId),
				new IranMobile()
			],
			'telephone' => ['nullable', 'numeric', 'digits:11'],
			'address' => ['required', 'string'],
			'national_code' => [
				'nullable',
				'numeric',
				'digits:10',
				Rule::unique('employees', 'national_code')->ignore($this->employeeId)
			],
			'employmented_at' => ['required', 'date'],
			'base_salary' => ['required', 'integer', 'min:1'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
