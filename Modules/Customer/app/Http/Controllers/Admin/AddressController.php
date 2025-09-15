<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Customer\Http\Requests\Admin\AddressStoreRequest;
use Modules\Customer\Http\Requests\Admin\AddressUpdateRequest;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;

class AddressController extends Controller implements HasMiddleware
{
	private Collection $customers;

	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_addresses', ['index']),
			new Middleware('permission:create_addresses', ['create', 'store']),
			new Middleware('permission:update_addresses', ['edit', 'update']),
			new Middleware('permission:delete_addresses', ['destroy']),
		];
	}

	public function __construct()
	{
		$this->customers = Customer::latest()->get(['id', 'full_name', 'mobile']);
	}

	public function index(): View
	{
		$addresses = Address::latest()
			->with('customer:id,full_name')
			->filters()
			->paginateOrAll();

		return view('customer::admin.address.index', [
			'addresses' => $addresses,
			'customers' => $this->customers,
		]);
	}

	public function create(): View
	{
		return view('customer::admin.address.create', [
			'customers' => $this->customers,
		]);
	}

	public function store(AddressStoreRequest $request)
	{
		$address = Address::create($request->validated());
		(new ActivityLogHelper($address))->created();

		if ($request->wantsJson()) {
			return response()->success('آدرس جدید با موفقیت برای مشتری ثبت شد', compact('address'));
		}

		return redirect()->back()->with('status', 'آدرس جدید با موفقیت برای مشتری ثبت شد');
	}

	public function edit(Address $address): View
	{
		$address->load('customer:id,mobile,full_name');

		return view('customer::admin.address.edit', compact('address'));
	}

	public function update(AddressUpdateRequest $request, Address $address): RedirectResponse
	{
		$address->update($request->validated());
		(new ActivityLogHelper($address))->created();

		return redirect()->back()->with('status', 'آدرس با موفقیت بروزرسانی شد');
	}

	public function destroy(Address $address): RedirectResponse
	{
		$address->delete();
		(new ActivityLogHelper($address))->deleted();

		return redirect()->back()->with('status', 'آدرس با موفقیت حذف شد');
	}
}
