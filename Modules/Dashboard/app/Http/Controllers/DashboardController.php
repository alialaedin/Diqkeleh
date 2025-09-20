<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Courier\Models\Courier;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;

class DashboardController extends Controller
{
	public function index()
	{
		$couriers = Courier::getAll();
		
		$inPersonOrders = Order::query()
			->whereNull('address_id')
			->where('status', OrderStatus::NEW)
			->select(['id', 'customer_id', 'shipping_amount', 'discount_amount', 'status', 'delivered_at', 'created_at'])
			->with(['customer:id,full_name,mobile', 'courier:id,full_name'])
			->get();

		$telephoneOrders = Order::query()
			->whereNotNull('address_id')
			->where('status', OrderStatus::NEW)
			->select(['id', 'customer_id', 'shipping_amount', 'discount_amount', 'status', 'delivered_at', 'created_at'])
			->with(['customer:id,full_name,mobile', 'courier:id,full_name'])
			->get();

		return view('dashboard::index', compact(['inPersonOrders', 'telephoneOrders', 'couriers']));
	}
}
