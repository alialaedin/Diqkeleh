<?php

namespace Modules\Permission\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Permission\Http\Requests\RoleStoreRequest;
use Modules\Permission\Http\Requests\RoleUpdateRequest;
use Modules\Permission\Models\Permission;
use Modules\Permission\Models\Role;

class RoleController extends Controller
{
	public function index()
	{
		$roles = Role::getAllRoles(false);

		return view('permission::role.index', compact('roles'));
	}

	public function create()
	{
		$permissions = Permission::getAll();

		return view('permission::role.create', compact('permissions'));
	}

	public function store(RoleStoreRequest $request)
	{
		$role = Role::create($request->only(['name', 'label']));
		$permissions = Permission::getAll()->whereIn('id', $request->permissions);
		$role->givePermissionTo($permissions);

		(new ActivityLogHelper($role))->created();

		return to_route('admin.roles.index')->with('status', 'نقش با موفقیت ایجاد شد');
	}

	public function edit(Role $role)
	{
		$role->load('permissions');
		$permissions = Permission::getAll();

		return view('permission::role.edit', compact(['role', 'permissions']));
	}

	public function update(RoleUpdateRequest $request, Role $role)
	{
		$role->update($request->only(['name', 'label']));
		$permissions = Permission::getAll()->whereIn('id', $request->permissions);
		$role->syncPermissions($permissions);

		(new ActivityLogHelper($role))->updated();

		return to_route('admin.roles.index')->with('status', 'نقش با موفقیت بروز شد');
	}

	public function destroy(Role $role)
	{
		$role->delete();
		(new ActivityLogHelper($role))->deleted();

		return to_route('admin.roles.index')->with('status', 'نقش با موفقیت حذف شد');
	}
}
