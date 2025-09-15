<?php

namespace Modules\Order\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Modules\Category\Models\Category;
use Modules\Courier\Models\Courier;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Http\Requests\Admin\Order\OrderChangeStatusRequest;
use Modules\Order\Http\Requests\Admin\Order\OrderStoreRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderCreatorService;
use Modules\Order\Services\OrderUpdaterService;
use Modules\Product\Models\Product;
use Modules\Setting\Models\Setting;

class OrderController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_orders', ['index', 'show', 'print']),
			new Middleware('permission:create_orders', ['create', 'store']),
			new Middleware('permission:update_orders', ['update', 'changeStatus']),
		];
	}

	public function index()
	{
		$statuses = OrderStatus::getCasesWithLabel();
		$orders = Order::query()
			->select(['id', 'customer_id', 'shipping_amount', 'discount_amount', 'status', 'delivered_at', 'created_at'])
			->with('customer:id,full_name,mobile')
			->latest()
			->filters()
			->paginateOrAll(100);

		return view('order::admin.order.index', compact(['orders', 'statuses']));
	}

	public function create()
	{
		$categories = Category::getCategoriesForAdmin();
		$couriers = Courier::getAll();
		$products = Product::getAllProducts()->load('store:id,product_id,balance');
		$defaultShippingAmount = Setting::getFromName('default_shipping_amount') ?? 0;

		return view('order::admin.order.create', compact(['categories', 'products', 'couriers', 'defaultShippingAmount']));
	}

	public function store(OrderStoreRequest $request)
	{
		DB::transaction(fn() => (new OrderCreatorService($request))->store());
		if ($request->wantsJson()) {
			return response()->success('سفارش جدید با موفقیت ایجاد شد');
		}

		return redirect()->back()->with('status', 'سفارش جدید با موفقیت ایجاد شد');
	}

	public function show(Order $order)
	{
		$products = Product::getAllProducts();
		$statuses = array_filter(
			array: OrderStatus::getCasesWithLabel(),
			callback: fn($status): bool => $order->status->value != $status['name']
		);

		$order->loadNecessaryRelations();

		return view('order::admin.order.show', compact(['order', 'statuses', 'products']));
	}

	public function changeStatus(OrderChangeStatusRequest $request, Order $order)
	{
		DB::transaction(fn() => (new OrderUpdaterService($order, $request))->changeStatus());

		return redirect()->back()->with('status', 'وضعیت سفارش با موفقیت بروزرسانی شد');
	}

	public function print(Order $order)
	{
		$order->loadNecessaryRelations();

		return view('order::admin.order.print', compact(['order']));
	}
}
