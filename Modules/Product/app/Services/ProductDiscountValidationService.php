<?php

namespace Modules\Product\Services;

use Illuminate\Http\Request;
use Modules\Core\Exceptions\ValidationException;
use Modules\Product\Enums\ProductDiscountType;

class ProductDiscountValidationService
{
	public function __construct(private Request $request) {}

	private function isDiscountDataFilled(): bool
	{
		return $this->request->filled(['discount', 'discount_type', 'discount_until']);
	}

	private function checkDiscountIsBiggerThanPrice(): void
	{
		if (
			$this->request->input('discount_type') === ProductDiscountType::FLAT &&
			$this->request->input('discount') > $this->request->input('unit_price')
		) {
			throw new ValidationException('تخفیف نمی‌تواند بیشتر از قیمت محصول یا تنوع باشد');
		}
	}

	private function checkDiscountAmount(): void
	{
		if (
			$this->request->input('discount_type') === ProductDiscountType::PERCENTAGE &&
			$this->request->input('discount') > 100
		) {
			throw new ValidationException('تخفیف نمی‌تواند بیشتر از ۱۰۰ درصد باشد');
		}
	}

	private function checkDiscountTypeProvided(): void
	{
		if (
			$this->request->input('discount') > 0 &&
			$this->request->input('discount_type') === null
		) {
			throw new ValidationException('ابتدا نوع تخفیف را انتخاب کنید');
		}
	}

	public function validate(): void
	{
		if (!$this->isDiscountDataFilled()) {
			return;
		}

		$this->checkDiscountIsBiggerThanPrice();
		$this->checkDiscountAmount();
		$this->checkDiscountTypeProvided();
	}
}
