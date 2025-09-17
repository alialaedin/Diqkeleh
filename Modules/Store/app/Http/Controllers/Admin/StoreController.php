<?php

namespace Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;
use Modules\Store\Http\Requests\Admin\ChangeBalanceRequest;
use Modules\Store\Models\Store;
use Modules\Store\Services\BalanceChangerService;

class StoreController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_stores', ['index']),
			new Middleware('permission:update_stores', ['store']),
		];
	}

	public function index()
	{
		$products = Product::getAllProductsForFilter();
		$categories = Category::getCategoriesForAdmin();

		$stores = Store::query()
			->with(['product:id,category_id,title', 'product.category:id,title'])
			->latest('id')
			->filters()
			->paginateOrAll();

		return view('store::store.index', compact(['stores', 'products', 'categories']));
	}

	public function store(ChangeBalanceRequest $request)
	{
		(new BalanceChangerService($request))->changeBalance();

		return redirect()->back()->with('status', 'موجودی محصول با موفقیت بروز شد');
	}
}
