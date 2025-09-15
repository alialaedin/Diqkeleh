<?php

namespace Modules\Category\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'title' => 'required|string|min:1|unique:categories,title',
			'en_title' => 'required|string|min:1|unique:categories,en_title',
			'description' => 'nullable|string',
			'status' => 'nullable|boolean',
		];
	}

	protected function passedValidation()
	{
		$this->merge([
			'status' => $this->status ? 1 : 0,
		]);
	}

	public function authorize(): bool
	{
		return true;
	}
}
