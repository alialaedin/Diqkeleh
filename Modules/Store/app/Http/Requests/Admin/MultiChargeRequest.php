<?php

namespace Modules\Store\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MultiChargeRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'products' => ['required', 'array'],
			'products.*.id' => ['bail', 'required', 'integer', 'exists:products,id'],
			'products.*.current_balance' => ['required', 'integer', 'min:0'],
			'products.*.new_balance' => ['nullable', 'integer', 'min:0']
		];
	}

	protected function passedValidation()
	{
		$products = collect($this->products)->filter(
			fn(array $p): bool => $p['new_balance'] != null && $p['new_balance'] != $p['current_balance']
		);

		$this->merge([
			'products' => $products->toArray()
		]);
	}

	public function authorize(): bool
	{
		return true;
	}
}
