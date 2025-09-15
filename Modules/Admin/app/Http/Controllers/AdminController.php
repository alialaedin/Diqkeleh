<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Admin\Http\Requests\AdminStoreRequest;
use Modules\Admin\Http\Requests\AdminUpdateRequest;
use Modules\Admin\Models\Admin;
use Modules\Permission\Models\Role;

class AdminController extends Controller implements HasMiddleware
{
	public static function middleware(): array
  {
		return [
			new Middleware('role:' . Role::SUPER_ADMIN_ROLE),
		];
	}
	
	public function index(): View
	{
		$admins = Admin::getAll();
		
		return view('admin::admin.index', compact('admins'));
	}

	public function show(Admin $admin): View
	{
		return view('admin::admin.show', compact('admin'));
	}

	public function create(): View
	{
		$roles = Role::getAllRoles(false);

		return view('admin::admin.create', compact('roles'));
	}

	public function store(AdminStoreRequest $request): RedirectResponse
	{
		Admin::storeOrUpdate($request);
		
		return redirect()->route('admin.admins.index')->with('status', 'ادمین با موفقیت ایجاد شد');
	}

	public function edit(Admin $admin): View
	{
		$roles = Role::getAllRoles(false);

		return view('admin::admin.edit', compact(['roles', 'admin']));
	}

	public function update(AdminUpdateRequest $request, Admin $admin): RedirectResponse
	{
		Admin::storeOrUpdate($request, $admin);

		return redirect()->route('admin.admins.index')->with('status', 'ادمین با موفقیت بروز شد');
	}

	public function destroy(Admin $admin): RedirectResponse
	{
		$admin->delete();

		return redirect()->route('admin.admins.index')->with('status', 'ادمین با موفقیت حذف شد');
	}
}
