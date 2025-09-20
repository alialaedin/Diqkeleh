<?php

namespace Modules\Product\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Helpers\Helpers;
use Modules\Product\Enums\ProductDiscountType;
use Modules\Product\Enums\ProductStatus;
use Modules\Product\Services\ProductDiscountValidationService;

class ProductUpdateRequest extends FormRequest
{
	private int $productId;
	protected function prepareForValidation()
	{
		$this->productId = Helpers::getModelIdFromUrl('product');
		$this->merge([
			'unit_price' => Helpers::removeComma($this->unit_price),
			'discount' => Helpers::removeComma($this->discount),
			'balance' => $this->filled('balance') ? $this->balance : 0,
			'has_daily_balance' => $this->boolean('has_daily_balance')
		]);
	}

	public function rules(): array
	{
		return [
			'title' => ['required', 'min:3', Rule::unique('products')->ignore($this->productId)],
			'category_id' => ['required', 'integer', 'exists:categories,id'],
			'unit_id' => ['required', 'integer', 'exists:units,id'],
			'status' => ['required', 'string', Rule::enum(ProductStatus::class)],
			'unit_price' => ['required', 'integer', 'min:1000'],
			'discount_type' => ['nullable', 'string', Rule::enum(ProductDiscountType::class)],
			'discount' => ['nullable'],
			'discount_until' => ['nullable', 'date'],
			'balance' => ['required', 'integer', 'min:0'],
			'has_daily_balance' => ['required', 'boolean']
		];
	}

	protected function passedValidation()
	{
		(new ProductDiscountValidationService($this))->validate();
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
