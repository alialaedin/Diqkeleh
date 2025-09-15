<?php

namespace Modules\Accounting\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Accounting\Enums\HeadlineType;
use Modules\Accounting\Http\Requests\Admin\HeadlineStoreRequest;
use Modules\Accounting\Http\Requests\Admin\HeadlineUpdateRequest;
use Modules\Accounting\Models\Headline;
use Modules\Activity\Helpers\ActivityLogHelper;

class HeadlineController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_headlines', ['index']),
			new Middleware('permission:create_headlines', ['create', 'store']),
			new Middleware('permission:update_headlines', ['edit', 'update']),
			new Middleware('permission:delete_headlines', ['destroy']),
		];
	}

	public function index(): View
	{
		$headlines = Headline::getAll();

		return view('accounting::admin.headline.index', compact('headlines'));
	}

	public function create(): View
	{
		$types = HeadlineType::getCasesWithLabel();

		return view('accounting::admin.headline.create', compact('types'));
	}

	public function store(HeadlineStoreRequest $request): RedirectResponse
	{
		$headline = Headline::create($request->validated());
		(new ActivityLogHelper($headline))->created();

		return to_route('admin.headlines.index')->with('status', 'سرفصل با موفقیت ثبت شد');
	}

	public function edit(Headline $headline): View
	{
		$types = HeadlineType::getCasesWithLabel();

		return view('accounting::admin.headline.edit', compact(['headline', 'types']));
	}

	public function update(HeadlineUpdateRequest $request, Headline $headline): RedirectResponse
	{
		$headline->update($request->validated());
		(new ActivityLogHelper($headline))->updated();

		return to_route('admin.headlines.index')->with('status', 'سرفصل با موفقیت بروز شد');
	}

	public function destroy(Headline $headline): RedirectResponse
	{
		$headline->delete();
		(new ActivityLogHelper($headline))->deleted();

		return to_route('admin.headlines.index')->with('status', 'سرفصل با موفقیت حذف شد');
	}
}
