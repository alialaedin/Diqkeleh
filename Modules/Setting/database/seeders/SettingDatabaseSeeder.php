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
				'type' => SettingType::TIME,
				'name' => 'daily_balance_charger_run_time',
				'label' => 'ساعت شاژ روزانه محصول',
			],
			[
				'type' => SettingType::PRICE,
				'name' => 'default_shipping_amount',
				'label' => 'هزینه ارسال (تومان)',
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
