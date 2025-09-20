<?php

namespace Modules\Customer\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;

class RangeUpdateRequest extends FormRequest
{
	public function prepareForValidation(): void
	{
		$this->merge([
			'shipping_amount' => Helpers::removeComma($this->shipping_amount) ?? 0,
			'status' => $this->boolean('status')
		]);
	}

	public function rules(): array
	{
		$rules = (new RangeStoreRequest())->rules();

		return [
			...$rules,
			'title' => [
				'required',
				'string',
				'min:3',
				'max:100',
				Rule::unique('ranges')->ignoreModel($this->route('range'))
			],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
