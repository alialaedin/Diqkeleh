<?php

namespace Modules\Auth\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Auth\Http\Requests\Admin\LoginRequest;
use Modules\Auth\Http\Requests\Admin\LogoutRequest;
use Modules\Auth\Services\Admin\AuthenticationService;

class AuthController extends Controller
{
	public function showLoginForm()
	{
		return view('auth::admin.login');
	}

	public function login(LoginRequest $request): RedirectResponse
	{
		$authenticationService = new AuthenticationService($request);
		$authenticationService->login();
		
		return redirect()->intended('/admin/dashboard');
	}

	public function logout(LogoutRequest $request): RedirectResponse
	{
		$authenticationService = new AuthenticationService($request);
		$authenticationService->logout();

		return redirect()->route('admin.login-form');
	}
}
