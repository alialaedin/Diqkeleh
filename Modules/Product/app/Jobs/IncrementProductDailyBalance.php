<?php

namespace Modules\Product\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Admin;
use Modules\Product\Models\Product;
use Modules\Store\Enums\StoreType;
use Modules\Store\Models\Store;

class IncrementProductDailyBalance
{
  use Dispatchable, InteractsWithQueue, SerializesModels;
  
  private Collection $products;
  private ?Admin $admin;

  public function __construct()
  {
    $this->setEligibleProducts();
    $this->setAdmin();
  }

  public function handle(): void
  {
    if ($this->products->isEmpty()) {
      return;
    }

    if (!$this->admin) {
      return;
    }

    [$storeUpdates, $transactions] = $this->prepareUpdatesAndTransactions();

    $this->applyUpdates($storeUpdates, $transactions);
  }

  private function setEligibleProducts(): void
  {
    $this->products = Product::query()
      ->whereNotNull('daily_balance')
      ->where('daily_balance', '>', 0)
      ->select(['id', 'daily_balance'])
      ->with('store:id,product_id,balance')
      ->get();
  }

  private function setAdmin(): void
  {
    $this->admin = Admin::query()->select('id')->first();
  }

  private function prepareUpdatesAndTransactions(): array
  {
    $storeUpdates = [];
    $transactions = [];

    foreach ($this->products as $product) {
      $store = $product->store;

      if (!$store) {
        continue;
      }

      $diff = $product->daily_balance - $store->balance;

      if ($diff === 0) {
        continue;
      }

      $storeUpdates[] = [
        'id' => $store->id,
        'balance' => $product->daily_balance,
      ];

      $transactions[] = $this->createTransaction($store->id, $diff, $this->admin);
    }

    return [$storeUpdates, $transactions];
  }

  private function createTransaction(int $storeId, int $diff, $admin): array
  {
    return [
      'store_id' => $storeId,
      'type' => $diff > 0 ? StoreType::INCREMENT : StoreType::DECREMENT,
      'quantity' => abs($diff),
      'description' => 'موجودی روزانه محصول',
      'creatorable_type' => Admin::class,
      'creatorable_id' => $admin->id,
      'updaterable_type' => Admin::class,
      'updaterable_id' => $admin->id,
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }

  private function applyUpdates(array $storeUpdates, array $transactions): void
  {
    DB::transaction(function () use ($storeUpdates, $transactions) {
      if (!empty($storeUpdates)) {
        Store::upsert($storeUpdates, ['id'], ['balance']);
      }

      if (!empty($transactions)) {
        DB::table('store_transactions')->insert($transactions);
      }
    });
  }
}
