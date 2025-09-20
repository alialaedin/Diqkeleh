<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Customer\Http\Requests\Admin\RangeStoreRequest;
use Modules\Customer\Http\Requests\Admin\RangeUpdateRequest;
use Modules\Customer\Models\Range;

class RangeController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_ranges', ['index']),
			new Middleware('permission:create_ranges', ['create', 'store']),
			new Middleware('permission:update_ranges', ['edit', 'update']),
			new Middleware('permission:delete_ranges', ['destroy']),
		];
	}

	public function index()
	{
		$ranges = Range::getAll();

		return view('customer::admin.range.index', compact('ranges'));
	}

	public function create()
	{
		return view('customer::admin.range.create');
	}

	public function store(RangeStoreRequest $request)
	{
		$range = Range::create($request->validated());
		(new ActivityLogHelper($range))->created();

		return to_route('admin.ranges.index')->with('status', 'محدوده با موفقیت ایجاد شد');
	}

	public function edit(Range $range)
	{
		return view('customer::admin.range.edit', compact('range'));
	}

	public function update(RangeUpdateRequest $request, Range $range)
	{
		$range->update($request->validated());
		(new ActivityLogHelper($range))->updated();

		return to_route('admin.ranges.index')->with('status', 'محدوده با موفقیت بروز شد');
	}

	public function destroy(Range $range)
	{
		$range->delete();
		(new ActivityLogHelper($range))->deleted();

		return to_route('admin.ranges.index')->with('status', 'محدوده با موفقیت حذف شد');
	}
}
