<?php

namespace Modules\Wallet\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Wallet\Http\Requests\Admin\WalletDepositRequest;
use Modules\Wallet\Http\Requests\Admin\WalletWithdrawRequest;
use Modules\Wallet\Services\WalletService;

class WalletController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:create_walletTransaction'),
		];
	}

	public function deposit(WalletDepositRequest $request)
	{
		$service = new WalletService($request->customer_id);
		$service->deposit(
			$request->input('amount'),
			$request->input('description'),
			$request->boolean('deposit_gift_balance'),
			$request->boolean('send_sms'),
		);

		return redirect()->back()->with('status', 'موجودی کیف پول با موفقیت افزایش یافت');
	}

	public function withdraw(WalletWithdrawRequest $request)
	{
		$service = new WalletService($request->customer_id);
		$service->withdraw(
			$request->input('amount'),
			$request->input('description'),
			$request->boolean('withrdaw_gift_balance_too'),
			$request->boolean('send_sms'),
		);

		return redirect()->back()->with('status', 'موجودی کیف پول با موفقیت کاهش یافت');
	}
}
