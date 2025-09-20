<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Store\Enums\StoreType;
use Modules\Store\Http\Requests\Admin\MultiChargeRequest;
use Modules\Store\Services\BalanceChangerService;

class StoreMultiChargeController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_stores', ['index']),
			new Middleware('permission:update_stores', ['update']),
		];
	}

	public function index()
	{
		$products = Product::getAllProducts()
			->filter(fn(Product $p) => $p->has_daily_balance)
			->load('store:id,product_id,balance');

		return view('store::multi-charge.index', compact('products'));
	}

	public function update(MultiChargeRequest $request)
	{
		DB::transaction(function () use ($request) {
			foreach ($request->products as $product) {

				$oldBalance = $product['current_balance'];
				$newBalance = $product['new_balance'];

				$diff = $newBalance - $oldBalance;
				$type = $diff > 0 ? StoreType::INCREMENT : StoreType::DECREMENT;

				$data = (object) [
					'product_id' => $product['id'],
					'quantity' => abs($diff),
					'type' => $type,
					'description' => 'بروزرسانی موجودی در فرایند شارژ گروهی'
				];

				(new BalanceChangerService($data))->changeBalance();
			}
		});

		return redirect()->back()->with('status', 'موجودی محصولات با موفقیت بروزرسانی شد');
	}
}
