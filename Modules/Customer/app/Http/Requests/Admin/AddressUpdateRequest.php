<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'address' => 'required|string|max:500',
			'range_id' => 'required|integer|exists:ranges,id',
			'mobile' => 'required|string|size:11',
			'postal_code' => 'nullable|numeric|digits:11'
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
