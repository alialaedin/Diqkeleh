<?php

namespace Modules\Wallet\Services;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Customer\Models\Customer;
use Modules\Admin\Models\Admin;
use Modules\Activity\Helpers\ActivityLogHelper;
use Modules\Core\Exceptions\ValidationException;
use Modules\Sms\Sms;
use Modules\Wallet\Enums\WalletTransactionType;
use Modules\Wallet\Models\Wallet;

class WalletService
{
	private Customer $customer;
	private Wallet $wallet;
	private Authenticatable $admin;

	public function __construct(Customer|int $customer)
	{
		$this->customer = $customer instanceof Customer
			? $customer
			: Customer::findOrFail($customer);

		$this->wallet = $this->customer->wallet;
		$this->admin = auth(Admin::GUARD_NAME)->user();
	}

	public function deposit(int $amount, string $description, bool $depositGiftBalance = false, bool $sendSms = false): void
	{
		$this->updateBalance($amount, $depositGiftBalance);
		$this->createTransaction($amount, $description, WalletTransactionType::DEPOSIT);
		$this->logActivity(WalletTransactionType::DEPOSIT, $amount);

		if ($sendSms) {
			$this->sendSms(WalletTransactionType::DEPOSIT, $amount);
		}
	}

	public function withdraw(int $amount, string $description, bool $withdrawGiftBalanceToo = false, bool $sendSms = false): void
	{
		$maxAllowedAmount = $withdrawGiftBalanceToo ? $this->wallet->balance : $this->wallet->main_balance;

		if ($amount > $maxAllowedAmount) {
			throw new ValidationException('مبلغ درخواستی بیشتر از موجودی کاربر است');
		}

		$this->deductBalance($amount);
		$this->createTransaction($amount, $description, WalletTransactionType::WITHDRAW);
		$this->logActivity(WalletTransactionType::WITHDRAW, $amount);

		if ($sendSms) {
			$this->sendSms(WalletTransactionType::WITHDRAW, $amount);
		}
	}

	private function logActivity(WalletTransactionType $type, int $amount): void
	{
		$description = match ($type) {
			WalletTransactionType::DEPOSIT => "افزایش موجودی کیف پول {$this->customer->full_name} به میزان " . number_format($amount) . " تومان توسط {$this->admin->name}",
			WalletTransactionType::WITHDRAW => "کاهش موجودی کیف پول {$this->customer->full_name} به میزان " . number_format($amount) . " تومان توسط {$this->admin->name}",
		};

		(new ActivityLogHelper($this->wallet, $description))->created();
	}

	private function updateBalance(int $amount, bool $isGift): void
	{
		if ($isGift) {
			$this->wallet->gift_balance += $amount;
		} else {
			$this->wallet->main_balance += $amount;
		}
		$this->wallet->save();
	}

	private function deductBalance(int $amount): void
	{
		if ($amount > $this->wallet->main_balance) {
			$diff = $amount - $this->wallet->main_balance;
			$this->wallet->main_balance = 0;
			$this->wallet->gift_balance -= $diff;
		} else {
			$this->wallet->main_balance -= $amount;
		}
		$this->wallet->save();
	}

	private function createTransaction(int $amount, string $description, WalletTransactionType $type): void
	{
		$this->wallet->transactions()->create([
			'amount' => $amount,
			'description' => $description,
			'type' => $type,
		]);
	}

	private function sendSms(WalletTransactionType $type, int $amount)
	{
		$key = match ($type) {
			WalletTransactionType::DEPOSIT => 'deposit_wallet',
			WalletTransactionType::WITHDRAW => 'withdraw_wallet',
		};

		if (app()->isProduction()) {
			$pattern = config('sms-patterns.' . $key);
			Sms::pattern($pattern)
				->data([
					'token' => number_format($amount),
					'token1' => $this->customer->full_name,
				])
				->to([$this->customer->mobile])
				->send();
		}
	}
}
