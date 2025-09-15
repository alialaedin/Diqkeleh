<?php

namespace Modules\Employee\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Employee\Models\Employee;
use Modules\Employee\Http\Requests\Admin\Employee\StoreRequest as EmployeeStoreRequest;
use Modules\Employee\Http\Requests\Admin\Employee\UpdateRequest as EmployeeUpdateRequest;

class EmployeeController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_employees', ['index', 'show']),
			new Middleware('permission:create_employees', ['create', 'store']),
			new Middleware('permission:update_employees', ['edit', 'update']),
			new Middleware('permission:delete_employees', ['destroy']),
		];
	}

	public function index()
	{
		$employees = Employee::getAll();

		return view('employee::admin.employee.index', compact('employees'));
	}

	public function create()
	{
		return view('employee::admin.employee.create');
	}

	public function store(EmployeeStoreRequest $request)
	{
		$employee = Employee::create($request->validated());
		(new ActivityLogHelper($employee))->created();

		return to_route('admin.employees.index')->with('status', 'کارمند با موفقیت ایجاد شد');
	}

	public function show(Employee $employee)
	{
		return view('employee::admin.employee.show', compact('employee'));
	}

	public function edit(Employee $employee)
	{
		return view('employee::admin.employee.edit', compact('employee'));
	}

	public function update(EmployeeUpdateRequest $request, Employee $employee)
	{
		$employee->update($request->validated());
		(new ActivityLogHelper($employee))->updated();

		return to_route('admin.employees.index')->with('status', 'کارمند با موفقیت بروز شد');
	}

	public function destroy(Employee $employee)
	{
		$employee->delete();
		(new ActivityLogHelper($employee))->deleted();

		return to_route('admin.employees.index')->with('status', 'کارمند با موفقیت حذف شد');
	}
}
