<?php

namespace Modules\Product\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Category\Models\Category;
use Modules\Product\Enums\ProductDiscountType;
use Modules\Product\Enums\ProductStatus;
use Modules\Product\Http\Requests\Admin\ProductStoreRequest;
use Modules\Product\Http\Requests\Admin\ProductUpdateRequest;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductOperationsService;
use Modules\Unit\Models\Unit;

class ProductController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_products', ['index', 'show']),
			new Middleware('permission:create_products', ['create', 'store']),
			new Middleware('permission:update_products', ['edit', 'update']),
			new Middleware('permission:delete_products', ['destroy']),
		];
	}

	public function index()
	{
		$products = Product::query()->latest('id')->filters()->paginateOrAll();
		$statuses = ProductStatus::getCasesWithLabel();
		$categories = Category::getCategoriesForAdmin();

		return view('product::admin.index', compact(['products', 'statuses', 'categories']));
	}

	public function show(Product $product)
	{
		return view('product::admin.show', compact('product'));
	}

	public function create()
	{
		$statuses = ProductStatus::getCasesWithLabel();
		$discountTypes = ProductDiscountType::getCasesWithLabel();
		$categories = Category::getCategoriesForAdmin();
		$units = Unit::getAll(true);

		return view('product::admin.create', compact(['statuses', 'discountTypes', 'categories', 'units']));
	}

	public function store(ProductStoreRequest $request)
	{
		(new ProductOperationsService($request))->create();

		return to_route('admin.products.index')->with('status', 'محصول با موفقیت ایجاد شد');
	}

	public function edit(Product $product)
	{
		$statuses = ProductStatus::getCasesWithLabel();
		$discountTypes = ProductDiscountType::getCasesWithLabel();
		$categories = Category::getCategoriesForAdmin();
		$units = Unit::getAll(true);
		$product->load('store');

		return view('product::admin.edit', compact(['statuses', 'discountTypes', 'categories', 'product', 'units']));
	}

	public function update(ProductUpdateRequest $request, Product $product)
	{
		(new ProductOperationsService($request, $product))->update();

		return to_route('admin.products.index')->with('status', 'محصول با موفقیت ویرایش شد');
	}

	public function destroy(Product $product)
	{
		$product->delete();
		
		return to_route('admin.products.index')->with('status', 'محصول با موفقیت حذف شد');
	}
}
