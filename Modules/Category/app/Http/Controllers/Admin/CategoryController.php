<?php

namespace Modules\Category\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Category\Http\Requests\Admin\CategoryStoreRequest;
use Modules\Category\Http\Requests\Admin\CategoryUpdateRequest;
use Modules\Category\Models\Category;

class CategoryController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_categories', ['index']),
			new Middleware('permission:create_categories', ['create', 'store']),
			new Middleware('permission:update_categories', ['edit', 'update']),
			new Middleware('permission:delete_categories', ['destroy']),
		];
	}

	public function index()
	{
		$categories = Category::getCategoriesForAdmin();
		return view('category::admin.index', compact('categories'));
	}

	public function create()
	{
		return view('category::admin.create');
	}

	public function store(CategoryStoreRequest $request)
	{
		$category = Category::create($request->validated());
		(new ActivityLogHelper($category))->created();

		return to_route('admin.categories.index')->with('status', 'دسته بندی با موفقیت ایجاد شد');
	}

	public function edit(Category $category)
	{
		return view('category::admin.edit', compact('category'));
	}

	public function update(CategoryUpdateRequest $request, Category $category)
	{
		$category->update($request->validated());
		(new ActivityLogHelper($category))->updated();

		return to_route('admin.categories.index')->with('status', 'دسته بندی با موفقیت بروزرسانی شد');
	}

	public function destroy(Category $category)
	{
		$category->delete();
		(new ActivityLogHelper($category))->deleted();

		return to_route('admin.categories.index')->with('status', 'دسته بندی با موفقیت حذف شد');
	}
}
