<?php

namespace Modules\Permission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:100', 'unique:roles,name'],
			'label' => ['required', 'string', 'min:3', 'max:100', 'unique:roles,label'],
			'permissions' => ['required', 'array'],
			'permissions.*' => ['required', 'integer', 'exists:permissions,id'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
