<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class RangeStoreRequest extends FormRequest
{
	public function prepareForValidation(): void
	{
		$this->merge([
			'shipping_amount' => Helpers::removeComma($this->shipping_amount) ?? 0,
			'status' => $this->boolean('status')
		]);
	}

	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'min:3', 'max:100', 'unique:ranges'],
			'shipping_amount' => ['required', 'integer', 'min:0'],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
