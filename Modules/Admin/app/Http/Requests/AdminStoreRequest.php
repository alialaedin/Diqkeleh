<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Rules\IranMobile;

class AdminStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'username' => ['required', 'alpha_num', 'unique:admins,username'],
			'mobile' => ['required', 'numeric', 'unique:admins,mobile', new IranMobile()],
			'role_id' => ['required', 'integer', 'exists:roles,id'],
			'password' => ['required', 'string', Password::min(6), 'confirmed'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
