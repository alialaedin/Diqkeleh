<?php

namespace Modules\Store\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Store\Enums\StoreType;

class ChangeBalanceRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'product_id' => 'bail|required|integer|exists:products,id',
			'description' => 'required|string|max:1000',
			'type' => ['required', Rule::in(StoreType::cases())],
			'quantity' => 'required|integer|min:1'
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
