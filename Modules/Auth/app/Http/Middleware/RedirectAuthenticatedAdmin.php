<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Admin\Models\Admin;

class RedirectAuthenticatedAdmin
{
	public function handle(Request $request, Closure $next)
	{
		if (auth(Admin::GUARD_NAME)->check()) {
			return redirect()->route('admin.dashboard');
		}

		return $next($request);
	}
}
