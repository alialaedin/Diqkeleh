<?php

namespace Modules\Permission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Exceptions\ValidationException;
use Modules\Permission\Models\Role;

class RoleUpdateRequest extends FormRequest
{
	private Role $role;

	protected function prepareForValidation()
	{
		$this->role = $this->route('role');

		if ($this->role->name == Role::SUPER_ADMIN_ROLE) {
			throw new ValidationException("نقش سوپر ادمین قابل ویرایش نمی باشد");
		}

		if ($this->role->name == Role::CASHIER_ROLE) {
			throw new ValidationException("نقش صندوقدار قابل ویرایش نمی باشد");
		}
	}

	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('roles')->ignoreModel($this->role)],
			'label' => ['required', 'string', 'min:3', 'max:100', Rule::unique('roles')->ignoreModel($this->role)],
			'permissions' => ['required', 'array'],
			'permissions.*' => ['required', 'integer', 'exists:permissions,id'],
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
