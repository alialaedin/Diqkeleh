<?php

namespace Modules\Order\Http\Requests\Admin\OrderItem;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Exceptions\ValidationException;
use Modules\Product\Models\Product;

class AddNewItemRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'product_id' => ['required', 'integer', 'exists:products,id'],
			'quantity' => ['required', 'integer', 'min:1']
		];
	}

	protected function passedValidation()
	{
		$order = $this->route('order');
		$product = Product::with('store')->findOrFail($this->product_id);

		if ($order->items()->where('product_id', $this->product_id)->exists()) {
			throw new ValidationException('این محصول در لیست سفارش  کاربر موجود است');
		}

		$productBalance = $product->store->balance;
		$requestBalance = $this->quantity;

		if ($productBalance < $requestBalance) {
			throw new ValidationException("تعداد سفارش این محصول بیشتر از موجودی است. موجودی این محصول : {$productBalance}");
		}	

		$this->merge([
			'product' => $product
		]);
	}

	public function authorize(): bool
	{
		return true;
	}
}
