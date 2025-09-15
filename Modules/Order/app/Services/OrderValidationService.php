<?php

namespace Modules\Order\Services;

use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Modules\Core\Exceptions\ValidationException;
use Modules\Product\Enums\ProductStatus;
use Modules\Product\Models\Product;

class OrderValidationService
{
	private BaseCollection $requestProducts;
	private EloquentCollection $databaseProducts;
	private array $productIds;

	public function __construct(private readonly Request $request)
	{
		$this->initializeProperties();
	}

	private function initializeProperties(): void
	{
		$this->requestProducts = collect($this->request->input('products', []));

		if ($this->requestProducts->isEmpty()) {
			throw new ValidationException('هیچ محصولی برای سفارش انتخاب نشده است.');
		}

		$this->productIds = $this->requestProducts->pluck('id')->unique()->values()->all();

		$this->databaseProducts = Product::query()
			->with(['store' => function ($query) {
				$query->select('product_id', 'balance');
			}])
			->whereIn('id', $this->productIds)
			->select(['id', 'title', 'status'])
			->get();
	}

	private function validateProductCount(): void
	{
		if ($this->requestProducts->count() !== $this->databaseProducts->count()) {
			throw new ValidationException('تعداد محصولات وارد شده با محصولات موجود مطابقت ندارد.');
		}
	}

	private function validateProductAvailability(Product $product): void
	{
		if ($product->status === ProductStatus::OUT_OF_STOCK) {
			throw new ValidationException("محصول '{$product->title}' در وضعیت قابل فروش نیست.");
		}
	}

	private function validateProductQuantity(Product $product): void
	{
		$requestProduct = $this->requestProducts->firstWhere('id', $product->id);

		if (!$product->store) {
			throw new ValidationException("موجودی محصول '{$product->title}' یافت نشد.");
		}

		if ($product->store->balance < $requestProduct['quantity']) {
			throw new ValidationException("تعداد انتخاب شده برای محصول '{$product->title}' بیشتر از موجودی انبار است.");
		}
	}

	public function validate(): void
	{
		$this->validateProductCount();

		$errors = [];

		foreach ($this->databaseProducts as $product) {
			try {
				$this->validateProductAvailability($product);
				$this->validateProductQuantity($product);
			} catch (ValidationException $e) {
				$errors[] = $e->getMessage();
			}
		}

		if (!empty($errors)) {
			throw new ValidationException(implode("\n", $errors));
		}
	}
}
