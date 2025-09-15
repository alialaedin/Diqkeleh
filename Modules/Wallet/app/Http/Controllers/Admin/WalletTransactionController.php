<?php

namespace Modules\Wallet\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Models\WalletTransaction;

class WalletTransactionController extends Controller implements HasMiddleware
{
	public static function middleware(): array
	{
		return [
			new Middleware('permission:read_walletTransaction'),
		];
	}

	public function index()
	{
		$types = WalletTransactionType::getCasesWithLabel();
		$walletTransactions = WalletTransaction::query()
			->with([
				'wallet:id,customer_id',
				'wallet.customer:id,mobile,full_name'
			])
			->filters()
			->latest()
			->paginateOrAll();

		return view('wallet::admin.transactions', compact(['walletTransactions', 'types']));
	}
}
