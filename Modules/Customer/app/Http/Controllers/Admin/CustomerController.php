<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Core\Enums\BooleanStatus;
use Modules\Customer\Http\Requests\Admin\CustomerStoreRequest;
use Modules\Customer\Http\Requests\Admin\CustomerUpdateRequest;
use Modules\Customer\Models\Customer;

class CustomerController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_customers', ['index', 'show']),
			new Middleware('permission:create_customers', ['create', 'store']),
			new Middleware('permission:update_customers', ['edit', 'update']),
			new Middleware('permission:delete_customers', ['destroy']),
		];
	}

	public function index()
	{
		$customers = Customer::query()->latest()->filters()->paginateOrAll();
		$statuses = BooleanStatus::getCasesWithLabel();

		return view('customer::admin.customer.index', compact(['customers', 'statuses']));
	}

	public function show(Customer $customer): View
	{
		$customer->load([
			'addresses',
			'orders' => fn (HasMany $o) => $o->orderByDesc('id'),
			'orders.activeItems',
			'payments' => fn (HasMany $p) => $p->orderByDesc('id'),
			'walletTransactions' => fn (HasManyThrough $w) => $w->orderByDesc('id'),
		])
		->loadCount(['orders', 'payments', 'deposits', 'withdraws'])
		->append(['total_sales_amount', 'total_payment_amount', 'remaining_amount']);

		return view('customer::admin.customer.show', compact('customer'));
	}

	public function create(): View
	{
		$statuses = BooleanStatus::getCasesWithLabel();

		return view('customer::admin.customer.create', compact('statuses'));
	}

	public function store(CustomerStoreRequest $request): RedirectResponse
	{
		$customer = Customer::create($request->validated());
		(new ActivityLogHelper($customer))->created();

		return to_route('admin.customers.index')->with('status', 'مشتری جدید با موفقیت ایجاد شد');
	}

	public function edit(Customer $customer): View
	{
		$statuses = BooleanStatus::getCasesWithLabel();

		return view('customer::admin.customer.edit', compact(['statuses', 'customer']));
	}

	public function update(CustomerUpdateRequest $request, Customer $customer): RedirectResponse
	{
		$customer->update($request->validated());
		(new ActivityLogHelper($customer))->updated();

		return to_route('admin.customers.index')->with('status', 'مشتری با موفقیت بروزرسانی شد');
	}

	public function destroy(Customer $customer): RedirectResponse
	{
		$customer->delete();
		(new ActivityLogHelper($customer))->deleted();

		return to_route('admin.customers.index')->with('status', 'مشتری با موفقیت حذف شد');
	}

}
