<?php

namespace Modules\Core\Traits;

use Flasher\Toastr\Laravel\Facade\Toastr;

trait HasToastMessage
{
	public static function bootHasToastMessage(): void
	{
		static::created(fn() => Toastr::success('با موفقیت ایجاد شد'));
		static::updated(fn() => Toastr::success('با موفقیت بروز شد'));
		static::deleted(fn() => Toastr::success('با موفقیت حذف شد'));
	}
}
