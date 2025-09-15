<?php

return [
  Modules\Admin\Models\Admin::class => 'ادمین',
  Modules\Area\Models\City::class => 'شهر',
  Modules\Area\Models\Province::class => 'استان',
  Modules\Product\Models\Product::class => 'محصول',
  Modules\Category\Models\Category::class => 'دسته بندی',
  Modules\Setting\Models\Setting::class => 'تنظیمات',
  Modules\Store\Models\Store::class => 'انبار',
  Modules\Store\Models\StoreTransaction::class => 'تراکنش انبار',
  Modules\Payment\Models\Payment::class => 'پرداختی',
  Modules\Customer\Models\Customer::class => 'مشتری',
  Modules\Customer\Models\Address::class => 'آدرس',
  Modules\Order\Models\Order::class => 'سفارش',
  Modules\Order\Models\OrderItem::class => 'آیتم سفارش',
  Modules\Courier\Models\Courier::class => 'پیک',
  Modules\Permission\Models\Role::class => 'نقش',
  Modules\Unit\Models\Unit::class => 'واحد',
  Modules\Employee\Models\Employee::class => 'کارمند',
  Modules\Employee\Models\Salary::class => 'حقوق',
  Modules\Employee\Models\Account::class => 'حساب بانکی',
];