<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Product\Models\Product;
use Modules\Store\Enums\StoreType;
use Modules\Store\Models\StoreTransaction;

class StoreTransactionController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_storeTransactions', ['index']),
		];
	}

	public function index()
	{
		$transactions = StoreTransaction::query()
			->with([
				'store:id,product_id',
				'store.product:id,title'
			])
			->filters()
			->latest()
			->paginateOrAll();

		$products = Product::getAllProductsForFilter();
		$types = StoreType::getCasesWithLabel();

		return view('store::transaction.index', compact(['transactions', 'products', 'types']));
	}
}
