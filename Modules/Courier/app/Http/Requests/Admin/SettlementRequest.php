<?php

namespace Modules\Courier\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettlementRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'orders' => ['required', 'array'],
			'orders.*.id' => ['required', 'integer', 'exists:orders,id'],
			'orders.*.pos_amount' => ['nullable', 'integer', 'min:0'],
			'orders.*.cash_amount' => ['nullable', 'integer', 'min:0'],
			'orders.*.card_by_card_amount' => ['nullable', 'integer', 'min:0'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
