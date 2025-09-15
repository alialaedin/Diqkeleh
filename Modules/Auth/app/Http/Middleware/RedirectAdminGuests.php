<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Admin\Models\Admin;

class RedirectAdminGuests
{
  public function handle(Request $request, Closure $next)
  {
    if (
      !auth(Admin::GUARD_NAME)->check() &&
      str_contains($request->path(), 'admin') &&
      !$request->routeIs('admin.login-form')
    ) {
      return redirect()->route('admin.login-form');
    }

    return $next($request);
  }
}
