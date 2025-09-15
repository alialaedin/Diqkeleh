<?php

namespace Modules\Product\Services;

use Illuminate\Http\Request;
use Modules\Product\Models\Product;
use Modules\Store\Enums\StoreType;
use Modules\Store\Services\BalanceChangerService;

class ProductOperationsService
{
	public function __construct(
		private readonly Request $request,
		private Product $product = new Product()
	) {}

	public function create(): void
	{
		$this->product->fill($this->request->validated())->save();
		$this->setInitialBalance();
	}

	public function update(): void
	{
		$this->product->update($this->request->validated());
		$this->syncBalanceIfChanged();
	}

	private function setInitialBalance(): void
	{
		$initialBalance = (int) $this->request->input('initial_balance', 0);

		$this->product->store()->create(['balance' => $initialBalance]);

		if ($initialBalance > 0) {
			$this->dispatchBalanceChange(
				quantity: $initialBalance,
				type: StoreType::INCREMENT,
				description: 'موجودی اولیه محصول'
			);
		}
	}

	private function syncBalanceIfChanged(): void
	{
		$requestedBalance = (int) $this->request->input('balance');
		$currentBalance = $this->product->store->balance;

		if ($requestedBalance === $currentBalance) {
			return;
		}

		$diff = $requestedBalance - $currentBalance;
		$type = $diff < 0 ? StoreType::DECREMENT : StoreType::INCREMENT;

		$this->dispatchBalanceChange(
			quantity: abs($diff),
			type: $type,
			description: 'بروزرسانی موجودی محصول در ویرایش محصول'
		);
	}

	private function dispatchBalanceChange(int $quantity, StoreType $type, string $description): void
	{
		$data = (object) [
			'quantity' => $quantity,
			'type' => $type,
			'description' => $description,
			'product_id' => $this->product->id,
		];

		(new BalanceChangerService($data))->changeBalance();
	}
}
