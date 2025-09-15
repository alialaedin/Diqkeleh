<?php

namespace Modules\Employee\Http\Requests\Admin\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
	public function rules(): array
	{
		$ac = $this->route('account'); 
		
		return [
			'employee_id' => ['required', 'integer', 'exists:employees,id'],
			'bank_name' => ['required', 'string', 'min:3', 'max:50'],
			'sheba_number' => ['required', 'numeric', 'max_digits:30', Rule::unique('accounts')->ignoreModel($ac)],
			'card_number' => ['required', 'numeric', 'digits:16', Rule::unique('accounts')->ignoreModel($ac)]
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
