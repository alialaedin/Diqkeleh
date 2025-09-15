<?php

namespace Modules\Unit\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UnitStoreRequest extends FormRequest
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
			'name' => ['required', 'string', 'min:3', 'max:100', 'unique:units,name'],
			'label' => ['required', 'string', 'min:3', 'max:100'],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
