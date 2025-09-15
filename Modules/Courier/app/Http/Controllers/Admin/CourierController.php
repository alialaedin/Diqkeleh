<?php

namespace Modules\Courier\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Courier\Enums\CourierType;
use Modules\Courier\Http\Requests\Admin\CourierStoreRequest;
use Modules\Courier\Http\Requests\Admin\CourierUpdateRequest;
use Modules\Courier\Models\Courier;

class CourierController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_couriers', ['index']),
			new Middleware('permission:create_couriers', ['create', 'store']),
			new Middleware('permission:update_couriers', ['edit', 'update']),
			new Middleware('permission:delete_couriers', ['destroy']),
		];
	}

	public function index()
	{
		$couriers = Courier::getAll();

		return view('courier::admin.index', compact('couriers'));
	}

	public function create()
	{
		$types = CourierType::getCasesWithLabel();

		return view('courier::admin.create', compact('types'));
	}

	public function store(CourierStoreRequest $request)
	{
		$courier = Courier::create($request->validated());
		(new ActivityLogHelper($courier))->created();

		return to_route('admin.couriers.index')->with('status', 'پیک جدید با موفقیت ایجاد شد');
	}

	public function edit(Courier $courier)
	{
		$types = CourierType::getCasesWithLabel();

		return view('courier::admin.edit', compact(['types', 'courier']));
	}

	public function update(CourierUpdateRequest $request, Courier $courier)
	{
		$courier->update($request->validated());
		(new ActivityLogHelper($courier))->updated();

		return to_route('admin.couriers.index')->with('status', 'پیک جدید با موفقیت بروز شد');
	}

	public function destroy(Courier $courier)
	{
		$courier->delete();
		(new ActivityLogHelper($courier))->deleted();

		return to_route('admin.couriers.index')->with('status', 'پیک جدید با موفقیت حذف شد');
	}
}
