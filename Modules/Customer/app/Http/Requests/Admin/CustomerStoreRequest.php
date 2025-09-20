<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\IranMobile;

class CustomerStoreRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$this->merge([
			'status' => $this->status ? 1 : 0
		]);
	}

	public function rules(): array
	{
		return [
			'full_name' => 'nullable|string|min:3|max:120',
			'mobile' => ['required', 'unique:customers', new IranMobile()],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
