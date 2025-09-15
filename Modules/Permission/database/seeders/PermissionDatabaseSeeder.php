<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Admin;

class PermissionDatabaseSeeder extends Seeder
{
	public function run(): void
	{
		$data = [

			['name' => 'read_couries', 'label' => 'نمایش پیک ها'],
			['name' => 'create_couries', 'label' => 'ثبت پیک ها'],
			['name' => 'update_couries', 'label' => 'ویرایش پیک ها'],
			['name' => 'delete_couries', 'label' => 'حذف پیک ها'],

			['name' => 'read_categories', 'label' => 'نمایش دسته بندی ها'],
			['name' => 'create_categories', 'label' => 'ثبت دسته بندی ها'],
			['name' => 'update_categories', 'label' => 'ویرایش دسته بندی ها'],
			['name' => 'delete_categories', 'label' => 'حذف دسته بندی ها'],

			['name' => 'read_customers', 'label' => 'نمایش مشتریان'],
			['name' => 'create_customers', 'label' => 'ثبت مشتریان'],
			['name' => 'update_customers', 'label' => 'ویرایش مشتریان'],
			['name' => 'delete_customers', 'label' => 'حذف مشتریان'],

			['name' => 'read_addresses', 'label' => 'نمایش آدرس ها'],
			['name' => 'create_addresses', 'label' => 'ثبت آدرس ها'],
			['name' => 'update_addresses', 'label' => 'ویرایش آدرس ها'],
			['name' => 'delete_addresses', 'label' => 'حذف آدرس ها'],

			['name' => 'read_products', 'label' => 'نمایش محصولات'],
			['name' => 'create_products', 'label' => 'ثبت محصولات'],
			['name' => 'update_products', 'label' => 'ویرایش محصولات'],
			['name' => 'delete_products', 'label' => 'حذف محصولات'],

			['name' => 'read_orders', 'label' => 'نمایش سفارشات'],
			['name' => 'create_orders', 'label' => 'ثبت سفارشات'],
			['name' => 'update_orders', 'label' => 'ویرایش سفارشات'],

			['name' => 'read_orderItems', 'label' => 'نمایش آیتم های سفارش'],
			['name' => 'create_orderItems', 'label' => 'ثبت آیتم های سفارش'],
			['name' => 'update_orderItems', 'label' => 'ویرایش آیتم های سفارش'],

			['name' => 'read_walletTransaction', 'label' => 'نمایش تراکنش های کیف پول'],
			['name' => 'create_walletTransaction', 'label' => 'بروزرسانی موجودی کیف پول'],

			['name' => 'read_payments', 'label' => 'نمایش پرداختی'],
			['name' => 'create_payments', 'label' => 'ثبت پرداختی'],
			['name' => 'update_payments', 'label' => 'ویرایش پرداختی'],
			['name' => 'delete_payments', 'label' => 'حذف پرداختی'],

			['name' => 'read_headlines', 'label' => 'نمایش سرفصل ها'],
			['name' => 'create_headlines', 'label' => 'ثبت سرفصل ها'],
			['name' => 'update_headlines', 'label' => 'ویرایش سرفصل ها'],
			['name' => 'delete_headlines', 'label' => 'حذف سرفصل ها'],

			['name' => 'read_revenues', 'label' => 'نمایش درآمد ها'],
			['name' => 'create_revenues', 'label' => 'ثبت درآمد ها'],
			['name' => 'update_revenues', 'label' => 'ویرایش درآمد ها'],
			['name' => 'delete_revenues', 'label' => 'حذف درآمد ها'],

			['name' => 'read_expenses', 'label' => 'نمایش هزینه ها'],
			['name' => 'create_expenses', 'label' => 'ثبت هزینه ها'],
			['name' => 'update_expenses', 'label' => 'ویرایش هزینه ها'],
			['name' => 'delete_expenses', 'label' => 'حذف هزینه ها'],

			['name' => 'read_employees', 'label' => 'نمایش کارمندان'],
			['name' => 'create_employees', 'label' => 'ثبت کارمندان'],
			['name' => 'update_employees', 'label' => 'ویرایش کارمندان'],
			['name' => 'delete_employees', 'label' => 'حذف کارمندان'],

			['name' => 'read_salaries', 'label' => 'نمایش حقوق ها'],
			['name' => 'create_salaries', 'label' => 'ثبت حقوق ها'],
			['name' => 'update_salaries', 'label' => 'ویرایش حقوق ها'],
			['name' => 'delete_salaries', 'label' => 'حذف حقوق ها'],

			['name' => 'read_accounts', 'label' => 'نمایش حساب ها'],
			['name' => 'create_accounts', 'label' => 'ثبت حساب ها'],
			['name' => 'update_accounts', 'label' => 'ویرایش حساب ها'],
			['name' => 'delete_accounts', 'label' => 'حذف حساب ها'],

			['name' => 'read_stores', 'label' => 'نمایش انبار'],
			['name' => 'update_stores', 'label' => 'بروزرسانی انبار'],
			['name' => 'read_storeTransactions', 'label' => 'نمایش تراکنش های انبار'],

		];

		$guard = Admin::GUARD_NAME;
		$timestamp = now();

		$data = array_map(fn($item) => [
			...$item,
			'created_at' => $timestamp,
			'updated_at' => $timestamp,
			'guard_name' => $guard
		], $data);

		DB::table('permissions')->insert($data);
	}
}
