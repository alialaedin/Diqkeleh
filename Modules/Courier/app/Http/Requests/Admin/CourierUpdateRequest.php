<?php

namespace Modules\Courier\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;
use Modules\Core\Rules\IranMobile;
use Modules\Courier\Enums\CourierType;

class CourierUpdateRequest extends FormRequest
{
	private readonly int $courierId;

	protected function prepareForValidation()
	{
		$this->courierId = Helpers::getModelIdFromUrl('courier');
	}
	
	public function rules(): array
	{
		return [
			'first_name' => ['required', 'string', 'min:3', 'max:191'],
			'last_name' => ['required', 'string', 'min:3', 'max:191'],
			'mobile' => ['required', 'numeric', Rule::unique('couriers')->ignore($this->courierId), 'digits:11', new IranMobile()],
			'telephone' => ['nullable', 'numeric', Rule::unique('couriers')->ignore($this->courierId)],
			'national_code' => ['required', 'numeric', Rule::unique('couriers')->ignore($this->courierId), 'digits:10'],
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
