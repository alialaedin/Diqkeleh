<?php

namespace Modules\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Enums\SettingType;

class SettingDatabaseSeeder extends Seeder
{
	public function run(): void
	{
		$data = [
			[
				'type' => SettingType::PRICE,
				'name' => 'default_shipping_amount',
				'label' => 'هزینه ارسال (تومان)',
				'value' => 0
			],
			[
				'type' => SettingType::BOOLEAN,
				'name' => 'send_wallet_sms_for_new_order',
				'label' => 'ارسال اس ام اس برداشت کیف پول در سفارش جدید',
				'value' => 0
			],
			[
				'type' => SettingType::BOOLEAN,
				'name' => 'use_gift_balance_in_order',
				'label' => 'استفاده از کیف پول هدیه در خرید',
				'value' => 1
			],
			[
				'type' => SettingType::BOOLEAN,
				'name' => 'send_delivered_order_sms',
				'label' => 'ارسال اس ام اس سفارش ارسال شده',
				'value' => 0
			],
			[
				'type' => SettingType::BOOLEAN,
				'name' => 'send_canceled_order_sms',
				'label' => 'ارسال اس ام اس سفارش کنسل شده',
				'value' => 0
			],
		];

		$timestamp = now();
		$data = array_map(fn($item) => [
			...$item,
			'created_at' => $timestamp,
			'updated_at' => $timestamp,
		], $data);

		DB::table('settings')->insert($data);
	}
}
