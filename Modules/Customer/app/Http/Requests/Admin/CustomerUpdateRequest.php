<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;
use Modules\Core\Rules\IranMobile;

class CustomerUpdateRequest extends FormRequest
{
	private int $customerId;

	protected function prepareForValidation()
	{
		$this->customerId = Helpers::getModelIdFromUrl('customer');
		$this->merge([
			'status' => $this->status ? 1 : 0
		]);
	}

	public function rules(): array
	{
		return [
			'first_name' => 'nullable|string|min:3|max:120',
			'last_name' => 'nullable|string|min:3|max:120',
			'mobile' => ['required', Rule::unique('customers')->ignore($this->customerId), new IranMobile()],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
