<?php

namespace Modules\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitUpdateRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$this->merge([
			'status' => $this->boolean('status')
		]);
	}

	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('units')->ignoreModel($this->route('unit'))],
			'label' => ['required', 'string', 'min:3', 'max:100'],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
