<?php

namespace Modules\Accounting\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Accounting\Enums\HeadlineType;

class HeadlineStoreRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$this->merge([
			'status' => $this->status ? 1 : 0
		]);
	}
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'min:3', 'max:190'],
			'type' => ['required', Rule::enum(HeadlineType::class)],
			'status' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
