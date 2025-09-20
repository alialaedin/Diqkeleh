<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'address' => 'required|string|max:500',
			'customer_id' => 'required|integer|exists:customers,id',
			'range_id' => 'required|integer|exists:ranges,id',
			'mobile' => 'required|numeric|digits:11',
			'postal_code' => ['nullable', 'numeric', 'max_digits:20']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
