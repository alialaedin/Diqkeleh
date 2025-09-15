<?php

namespace Modules\Wallet\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;

class WalletDepositRequest extends FormRequest
{
	public function prepareForValidation()
	{
		$mergeInputs = [
			'deposit_gift_balance' => $this->boolean('deposit_gift_balance'),
			'send_sms' => $this->boolean('send_sms')
		];

		if ($this->filled('amount')) {
			$mergeInputs['amount'] = Helpers::removeComma($this->amount);
		}

		$this->merge($mergeInputs);
	}

	public function rules(): array
	{
		return [
			'customer_id' => ['bail', 'required', 'integer', 'exists:customers,id'],
			'amount' => ['required', 'integer', 'min:1000'],
			'description' => ['required', 'string'],
			'deposit_gift_balance' => ['required', 'boolean'],
			'send_sms' => ['required', 'boolean'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
