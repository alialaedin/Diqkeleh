<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Customer\Models\Customer;
use Modules\Order\Enums\OrderStatus;
use Modules\Permission\Models\Role;
use Modules\Product\Models\Product;

class ReportController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('role:' . Role::SUPER_ADMIN_ROLE),
		];
	}

	public function customers()
	{
		$customers = Customer::query()
			->select(['id', 'full_name', 'mobile'])
			->with([
				'orders' => function ($ordersQuery) {
					$ordersQuery->where('status', "!=", OrderStatus::CANCELED);
					$ordersQuery->select(['id', 'customer_id', 'shipping_amount', 'discount_amount']);
				},
				'orders.activeItems',
				'payments:id,customer_id,amount'
			])
			->filters()
			->withCount(['activeOrders', 'payments'])
			->latest()
			->paginate(100)
			->withQueryString()
			->each(function (Customer $customer) {
				$customer->append(['total_sales_amount', 'total_payment_amount', 'remaining_amount']);
				$customer->makeHidden(['orders', 'payments']);
			});

		return view('report::customers', compact('customers'));
	}

	public function products()
	{
		$products = Product::query()
			->filters()
			->select(['id', 'title', 'created_at'])
			->with([
				'activeOrderItems:id,product_id,total_amount,order_id',
				'activeOrderItems.order:id,status',
			])
			->get()
			->each(function (Product $product) {
				$product->append(['sales_amount', 'sales_count']);
				$product->makeHidden('activeOrderItems');
			});

		return view('report::products', compact('products'));
	}
}
