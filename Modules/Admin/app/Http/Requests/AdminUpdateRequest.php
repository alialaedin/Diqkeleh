<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Rules\IranMobile;

class AdminUpdateRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'username' => [
				'required',
				'alpha_num',
				Rule::unique('admins', 'username')->ignoreModel($this->route('admin'))
			],
			'mobile' => [
				'required',
				'numeric',
				Rule::unique('admins', 'mobile')->ignoreModel($this->route('admin')),
				new IranMobile()
			],
			'role_id' => ['required', 'integer', 'exists:roles,id'],
			'password' => ['nullable', 'string', Password::min(6), 'confirmed'],
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
