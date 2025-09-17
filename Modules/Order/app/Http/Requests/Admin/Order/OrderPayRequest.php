<?php

namespace Modules\Order\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Helpers\Helpers;
use Modules\Payment\Http\Requests\Admin\PaymentStoreRequest;

class OrderPayRequest extends FormRequest
{
	protected function prepareForValidation()
	{
		if ($this->filled('amount')) {
			$this->merge([
				'amount' => Helpers::removeComma($this->amount)
			]);
		}
	}

	public function rules(): array
	{
		$rules = (new PaymentStoreRequest())->rules();

		return [...$rules];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
