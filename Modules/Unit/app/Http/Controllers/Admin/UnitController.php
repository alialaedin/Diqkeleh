<?php

namespace Modules\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Unit\Http\Requests\Admin\UnitStoreRequest;
use Modules\Unit\Http\Requests\Admin\UnitUpdateRequest;
use Modules\Unit\Models\Unit;

class UnitController extends Controller
{
	public function index()
	{
		$units = Unit::getAll();
		return view('unit::admin.index', compact('units'));
	}

	public function create()
	{
		return view('unit::admin.create');
	}

	public function store(UnitStoreRequest $request)
	{
		$unit = Unit::create($request->validated());
		(new ActivityLogHelper($unit))->created();

		return to_route('admin.units.index')->with('status', 'واحد با موفقیت ایجاد شد');
	}

	public function edit(Unit $unit)
	{
		return view('unit::admin.edit', compact('unit'));
	}

	public function update(UnitUpdateRequest $request, Unit $unit)
	{
		$unit->update($request->validated());
		(new ActivityLogHelper($unit))->updated();

		return to_route('admin.units.index')->with('status', 'واحد با موفقیت بروز شد');
	}

	public function destroy(Unit $unit)
	{
		$unit->delete();
		(new ActivityLogHelper($unit))->deleted();

		return to_route('admin.units.index')->with('status', 'واحد با موفقیت حذف شد');
	}
}
