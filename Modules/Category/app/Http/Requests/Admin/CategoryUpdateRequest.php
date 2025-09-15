<?php

namespace Modules\Category\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Category\Models\Category;

class CategoryUpdateRequest extends FormRequest
{
	private Category $category;

	protected function prepareForValidation()
	{
		$this->category = $this->route('category');
	}

	public function rules(): array
	{
		return [
			'title' => ['required', 'min:1', Rule::unique('categories', 'title')->ignoreModel($this->category)],
			'en_title' => ['required', 'min:1', Rule::unique('categories', 'en_title')->ignoreModel($this->category)],
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
