<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'first_name' => 'required|string|max:191',
			'last_name' => 'required|string|max:191',
			'address' => 'required|string|max:500',
			'customer_id' => 'required|integer|exists:customers,id',
			'mobile' => 'required|string|size:11',
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
