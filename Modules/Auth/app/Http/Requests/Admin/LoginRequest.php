<?php

namespace Modules\Auth\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'username' => ['required', 'max:20', 'exists:admins,username'],
			'password' => ['required', 'min:3'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
