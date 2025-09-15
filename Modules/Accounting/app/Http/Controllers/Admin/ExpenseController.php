<?php

namespace Modules\Accounting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Accounting\Enums\HeadlineType;
use Modules\Accounting\Http\Requests\Admin\ExpenseStoreRequest;
use Modules\Accounting\Http\Requests\Admin\ExpenseUpdateRequest;
use Modules\Accounting\Models\Expense;
use Modules\Accounting\Models\Headline;
use Modules\Activity\Helpers\ActivityLogHelper;

class ExpenseController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_expenses', ['index']),
			new Middleware('permission:create_expenses', ['create', 'store']),
			new Middleware('permission:update_expenses', ['edit', 'update']),
			new Middleware('permission:delete_expenses', ['destroy']),
		];
	}

	public function index(): View
	{
		$expenses = Expense::query()->latest()->filters()->paginateOrAll();
		$headlines = Headline::getAllByType(HeadlineType::EXPENSE);

		return view('accounting::admin.expense.index', compact(['headlines', 'expenses']));
	}

	public function create()
	{
		$headlines = Headline::getAllByType(HeadlineType::EXPENSE);

		return view('accounting::admin.expense.create', compact('headlines'));
	}

	public function store(ExpenseStoreRequest $request): RedirectResponse
	{
		$expense = Expense::create($request->validated());
		(new ActivityLogHelper($expense))->created();

		return to_route('admin.expenses.index')->with('status', 'هزینه با موفقیت ثبت شد');
	}

	public function edit(Expense $expense): View
	{
		$headlines = Headline::getAllByType(HeadlineType::EXPENSE);

		return view('accounting::admin.expense.edit', compact(['headlines', 'expense']));
	}

	public function update(ExpenseUpdateRequest $request, Expense $expense): RedirectResponse
	{
		$expense->update($request->validated());
		(new ActivityLogHelper($expense))->updated();

		return to_route('admin.expenses.index')->with('status', 'هزینه با موفقیت بروز شد');
	}

	public function destroy(Expense $expense): RedirectResponse
	{
		$expense->delete();
		(new ActivityLogHelper($expense))->deleted();

		return to_route('admin.expenses.index')->with('status', 'هزینه با موفقیت حذف شد');
	}
}
