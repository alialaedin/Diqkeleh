<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
use Modules\Customer\Models\Range;

class AddressController extends Controller implements HasMiddleware
{
	private Collection $customers;
	private Collection $ranges;

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
		$this->ranges = Range::getAll(true);
	}

	public function index(): View
	{
		$addresses = Address::query()->latest()->filters()->paginateOrAll();

		return view('customer::admin.address.index', [
			'addresses' => $addresses,
			'customers' => $this->customers,
			'ranges' => $this->ranges
		]);
	}

	public function create(): View
	{
		return view('customer::admin.address.create', [
			'customers' => $this->customers,
			'ranges' => $this->ranges
		]);
	}

	public function store(AddressStoreRequest $request)
	{
		$address = Address::query()->create($request->validated());
		(new ActivityLogHelper($address))->created();

		if ($request->wantsJson()) {
			$address->load(['range']);
			return response()->success('آدرس جدید با موفقیت برای مشتری ثبت شد', compact('address'));
		}

		return to_route('admin.addresses.index')->with('status', 'آدرس جدید با موفقیت برای مشتری ثبت شد');
	}

	public function edit(Address $address): View
	{
		$ranges = $this->ranges;

		return view('customer::admin.address.edit', compact('address', 'ranges'));
	}

	public function update(AddressUpdateRequest $request, Address $address): RedirectResponse
	{
		$address->update($request->validated());
		(new ActivityLogHelper($address))->created();

		return to_route('admin.addresses.index')->with('status', 'آدرس با موفقیت بروزرسانی شد');
	}

	public function destroy(Address $address): RedirectResponse
	{
		$address->delete();
		(new ActivityLogHelper($address))->deleted();

		return to_route('admin.addresses.index')->with('status', 'آدرس با موفقیت حذف شد');
	}
}
