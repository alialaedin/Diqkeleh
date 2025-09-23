<?php

namespace Modules\Order\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Category\Models\Category;
use Modules\Courier\Models\Courier;
use Modules\Customer\Models\Range;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Http\Requests\Admin\Order\OrderChangeStatusRequest;
use Modules\Order\Http\Requests\Admin\Order\OrderPayRequest;
use Modules\Order\Http\Requests\Admin\Order\OrderStoreRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderCreatorService;
use Modules\Order\Services\OrderUpdaterService;
use Modules\Payment\Enums\PaymentType;
use Modules\Payment\Models\Payment;
use Modules\Product\Models\Product;

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
		$couriers = Courier::getAll();
		$types = PaymentType::getCasesWithLabel();

		$orders = Order::query()
			->select(['id', 'customer_id', 'shipping_amount', 'discount_amount', 'status', 'delivered_at', 'created_at'])
			->with(['customer:id,full_name,mobile', 'courier:id,full_name'])
			->latest()
			->filters()
			->paginateOrAll(100);

		return view('order::admin.order.index', compact(['orders', 'statuses', 'couriers', 'types']));
	}

	public function create()
	{
		$ranges = Range::getAll();
		$categories = Category::getCategoriesForAdmin();
		$products = Product::getAllProducts()->load('store:id,product_id,balance');

		return view('order::admin.order.create', compact(['categories', 'products', 'ranges']));
	}

	public function store(OrderStoreRequest $request)
	{
		$order = DB::transaction(fn() => (new OrderCreatorService($request))->store());

		if ($request->wantsJson()) {
			$order->append('shamsi_created_at');
			return response()->success('سفارش جدید با موفقیت ایجاد شد', compact('order'));
		}

		return redirect()->back()->with('status', 'سفارش جدید با موفقیت ایجاد شد');
	}

	public function show(Order $order)
	{
		$products = Product::getAllProducts();
		$couriers = Courier::getAll();
		$payTypes = PaymentType::getCasesWithLabel();
		$statuses = array_filter(
			OrderStatus::getCasesWithLabel(),
			fn($status): bool => $order->status->value != $status['name']
		);

		$order->loadNecessaryRelations();

		return view('order::admin.order.show', compact(['order', 'statuses', 'products', 'couriers', 'payTypes']));
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

	public function pay(OrderPayRequest $request)
	{
		$payment = Payment::create($request->validated());
		$logService = new ActivityLogHelper(
			$payment,
			"پرداختی به میزان " . number_format($payment->amount) . " تومان برای {$payment->customer->full_name} ثبت شد"
		);
		$logService->created();

		return redirect()->back()->with('status', 'پرداختی با موفقیت ثبت شد');
	}
}
