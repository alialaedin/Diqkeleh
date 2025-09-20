<?php

namespace Modules\Product\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Enums\ProductDiscountType;
use Modules\Product\Enums\ProductStatus;
use Modules\Product\Services\ProductDiscountValidationService;

class ProductStoreRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$this->merge([
			'unit_price' => Helpers::removeComma($this->unit_price),
			'discount' => Helpers::removeComma($this->discount),
			'initial_balance' => $this->filled('initial_balance') ? $this->initial_balance : 0,
			'has_daily_balance' => $this->boolean('has_daily_balance')
		]);
	}

	public function rules(): array
	{
		return [
			'title' => ['required', 'min:3', 'unique:products,title'],
			'category_id' => ['required', 'integer', 'exists:categories,id'],
			'unit_id' => ['required', 'integer', 'exists:units,id'],
			'status' => ['required', 'string', Rule::enum(ProductStatus::class)],
			'unit_price' => ['required', 'integer', 'min:1000'],
			'discount_type' => ['nullable', 'string', Rule::enum(ProductDiscountType::class)],
			'discount' => ['nullable'],
			'discount_until' => ['nullable', 'date'],
			'initial_balance' => ['required', 'integer', 'min:0'],
			'has_daily_balance' => ['required', 'boolean']
		];
	}

	protected function passedValidation()
	{
		(new ProductDiscountValidationService($this))->validate();
	}

	public function authorize(): bool
	{
		return true;
	}
}
