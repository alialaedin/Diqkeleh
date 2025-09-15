<?php

namespace Modules\Courier\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Rules\IranMobile;
use Modules\Courier\Enums\CourierType;

class CourierStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'first_name' => ['required', 'string', 'min:3', 'max:191'],
			'last_name' => ['required', 'string', 'min:3', 'max:191'],
			'mobile' => ['required', 'numeric', 'unique:couriers', 'digits:11', new IranMobile()],
			'telephone' => ['nullable', 'numeric', 'unique:couriers'],
			'national_code' => ['required', 'numeric', 'unique:couriers', 'digits:10'],
			'type' => ['required', Rule::enum(CourierType::class)],
			'address' => ['required', 'string', 'min:5', 'max:1000']
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
