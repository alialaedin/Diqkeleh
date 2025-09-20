<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware): void {

		$middleware->redirectGuestsTo(fn(Request $request) => route('admin.login-form'));
		$middleware->redirectUsersTo(fn(Request $request) => route('admin.dashboard'));

		$middleware->alias([
			'role' => RoleMiddleware::class,
			'permission' => PermissionMiddleware::class,
			'role_or_permission' => RoleOrPermissionMiddleware::class,
		]);
	})
	->withExceptions(function (Exceptions $exceptions): void {
		//
	})
	->withSchedule(function (Schedule $schedule) {
		//
	})
	->create();
