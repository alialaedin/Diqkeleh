<?php

namespace Modules\Wallet\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Modules\Core\Helpers\Helpers;

class WalletWithdrawRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		$mergeInputs = [
			'withrdaw_gift_balance_too' => $this->boolean('withrdaw_gift_balance_too'),
			'send_sms' => $this->boolean('send_sms')
		];

		if ($this->filled('amount')) {
			$mergeInputs['amount'] = Helpers::removeComma($this->amount);
		}

		$this->merge($mergeInputs);
	}

	public function rules(): array
	{
		$rules = (new WalletDepositRequest())->rules();
		$rules = Arr::except($rules, 'deposit_gift_balance');

		return [
			...$rules,
			'withrdaw_gift_balance_too' => ['required', 'boolean']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
