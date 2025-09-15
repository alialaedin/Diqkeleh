<?php

namespace Modules\Employee\Http\Requests\Admin\Account;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'employee_id' => ['required', 'integer', 'exists:employees,id'],
			'bank_name' => ['required', 'string', 'min:3', 'max:50'],
			'sheba_number' => ['required', 'numeric', 'max_digits:30', 'unique:accounts,sheba_number'],
			'card_number' => ['required', 'numeric', 'digits:16', 'unique:accounts,card_number']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
