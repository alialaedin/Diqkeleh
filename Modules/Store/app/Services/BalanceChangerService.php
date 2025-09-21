<?php

namespace Modules\Store\Services;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Admin;
use Modules\Core\Exceptions\ValidationException;
use Modules\Product\Enums\ProductStatus;
use Modules\Product\Models\Product;
use Modules\Store\Enums\StoreType;
use Modules\Store\Models\Store;

class BalanceChangerService
{
	private Authenticatable $user;
	private Store $store;
	private Product $product;
	private StoreType $type;
	private int $quantity;
	private string $description;

	public function __construct(private object $data)
	{
		$this->initialize();
	}

	private function initialize(): void
	{
		$this->user = Auth::user();

		$this->store = Store::query()
			->with('product:id,title')
			->where('product_id', '=', $this->data->product_id)
			->firstOrFail();

		$this->product = $this->store->product;
		$this->type = is_string($this->data->type) ? StoreType::from($this->data->type) : $this->data->type;
		$this->quantity = (int) $this->data->quantity;
		$this->description = (string) $this->data->description;
	}

	public function changeBalance(): void
	{
		DB::transaction(function () {
			if ($this->user instanceof Admin) {
				$this->processAdminTransaction();
			}
		});
	}


	private function processAdminTransaction(): void
	{
		$this->ensureSufficientBalance();
		$this->updateBalance();
		$this->updateProductStatus();
		$this->createTransaction();
	}

	private function ensureSufficientBalance(): void
	{
		if ($this->type === StoreType::DECREMENT && $this->store->balance < $this->quantity) {
			throw new ValidationException(
				"موجودی محصول {$this->product->title} کمتر از {$this->quantity} عدد است."
			);
		}
	}

	private function updateBalance(): void
	{
		$newBalance = $this->type === StoreType::INCREMENT 
			? $this->store->balance += $this->quantity
			: $this->store->balance -= $this->quantity;
		
		$this->store->balance = $newBalance;
		$this->store->save();
		$this->store->refresh();
	}

	private function updateProductStatus()
	{
		if ($this->store->balance == 0) {
			$this->product->update([
				'status' => ProductStatus::OUT_OF_STOCK
			]);
		} else if ($this->store->balance > 0 && $this->product->status == ProductStatus::OUT_OF_STOCK) {
			$this->product->update([
				'status' => ProductStatus::AVAILABLE
			]);
		}
	}

	private function createTransaction(): void
	{
		$this->store->transactions()->create([
			'quantity' => $this->quantity,
			'description' => $this->description,
			'type' => $this->type,
		]);
	}
}
