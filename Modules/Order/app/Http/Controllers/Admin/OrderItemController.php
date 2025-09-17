<?php

namespace Modules\Order\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Modules\Order\Http\Requests\Admin\OrderItem\AddNewItemRequest;
use Modules\Order\Http\Requests\Admin\OrderItem\UpdateQuantityRequest;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Order\Services\OrderUpdaterService;

class OrderItemController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:create_orderItems', ['addItem']),
			new Middleware('permission:update_orderItems', ['updateQuantity', 'changeStatus']),
		];
	}

	public function addItem(AddNewItemRequest $request, Order $order)
	{
		DB::transaction(fn() => (new OrderUpdaterService($order, $request))->addItem());

		return redirect()->back()->with('status', 'آیتم جدید به سفارش اضافه شد');
	}

	public function updateQuantity(UpdateQuantityRequest $request, OrderItem $orderItem)
	{
		$service = new OrderUpdaterService($orderItem->order, $request);
		DB::transaction(fn() => $service->updateQuantity());

		return redirect()->back()->with('status', 'تعداد آیتم با موفقیت بروزرسانی شد');
	}

	public function changeStatus(Request $request, OrderItem $orderItem)
	{
		$service = new OrderUpdaterService($orderItem->order, $request);
		DB::transaction(fn() => $service->changeItemStatus());

		return redirect()->back()->with('status', 'وضعیت آیتم سفارش با موفقیت بروزرسانی شد');
	}
}
