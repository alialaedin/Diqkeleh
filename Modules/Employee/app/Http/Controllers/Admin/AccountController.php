<?php

namespace Modules\Employee\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Employee\Models\Account;
use Modules\Employee\Http\Requests\Admin\Account\StoreRequest as AccountStoreRequest;
use Modules\Employee\Http\Requests\Admin\Account\UpdateRequest as AccountUpdateRequest;
use Modules\Employee\Models\Employee;

class AccountController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_accounts', ['index']),
			new Middleware('permission:create_accounts', ['create', 'store']),
			new Middleware('permission:update_accounts', ['edit', 'update']),
			new Middleware('permission:delete_accounts', ['destroy']),
		];
	}

	public function index()
	{
		$accounts = Account::getAll();

		return view('employee::admin.account.index', compact('accounts'));
	}

	public function create()
	{
		$employees = Employee::getAll();

		return view('employee::admin.account.create', compact('employees'));
	}

	public function store(AccountStoreRequest $request)
	{
		$account = Account::create($request->validated());
		(new ActivityLogHelper($account))->created();

		return to_route('admin.accounts.index')->with('status', 'حساب بانکی با موفقیت ایجاد شد');
	}

	public function edit(Account $account)
	{
		$employees = Employee::getAll();

		return view('employee::admin.account.edit', compact(['account', 'employees']));
	}

	public function update(AccountUpdateRequest $request, Account $account)
	{
		$account->update($request->validated());
		(new ActivityLogHelper($account))->updated();

		return to_route('admin.accounts.index')->with('status', 'حساب بانکی با موفقیت بروز شد');
	}

	public function destroy(Account $account)
	{
		$account->delete();
		(new ActivityLogHelper($account))->deleted();

		return to_route('admin.accounts.index')->with('status', 'حساب بانکی با موفقیت حذف شد');
	}
}
