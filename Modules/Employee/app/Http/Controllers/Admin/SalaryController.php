<?php

namespace Modules\Employee\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Employee\Models\Salary;
use Modules\Employee\Http\Requests\Admin\Salary\StoreRequest as SalaryStoreRequest;
use Modules\Employee\Http\Requests\Admin\Salary\UpdateRequest as SalaryUpdateRequest;
use Modules\Employee\Models\Employee;

class SalaryController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_salaries', ['index']),
			new Middleware('permission:create_salaries', ['create', 'store']),
			new Middleware('permission:update_salaries', ['edit', 'update']),
			new Middleware('permission:delete_salaries', ['destroy']),
		];
	}

	public function index()
	{
		$salaries = Salary::query()->latest()->paginateOrAll();

		return view('employee::admin.salary.index', compact('salaries'));
	}

	public function create()
	{
		$employees = Employee::getAll();

		return view('employee::admin.salary.create', compact('employees'));
	}

	public function store(SalaryStoreRequest $request)
	{
		$salary = Salary::create($request->validated());
		(new ActivityLogHelper($salary))->created();

		return to_route('admin.salaries.index')->with('status', 'فیش واریزی با موفقیت ایجاد شد');
	}

	public function edit(Salary $salary)
	{
		$employees = Employee::getAll();

		return view('employee::admin.salary.edit', compact(['salary', 'employees']));
	}

	public function update(SalaryUpdateRequest $request, Salary $salary)
	{
		$salary->update($request->validated());
		(new ActivityLogHelper($salary))->updated();

		return to_route('admin.salaries.index')->with('status', 'فیش واریزی با موفقیت بروز شد');
	}

	public function destroy(Salary $salary)
	{
		$salary->delete();
		(new ActivityLogHelper($salary))->deleted();

		return to_route('admin.salaries.index')->with('status', 'فیش واریزی با موفقیت حذف شد');
	}
}
