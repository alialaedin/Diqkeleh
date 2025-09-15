<?php

namespace Modules\Store\Enums;

use Illuminate\Support\Arr;

enum StoreType: string
{
	case INCREMENT = 'increment';
	case DECREMENT = 'decrement';

	public function label(): string
	{
		return match ($this) {
			self::INCREMENT => 'افزایش',
			self::DECREMENT => 'کاهش',
		};
	}

	public function color(): string
	{
		return match ($this) {
			self::INCREMENT => 'success',
			self::DECREMENT => 'danger',
		};
	}

	public static function getCasesWithLabel()
	{
		return Arr::map(self::cases(), function (self $type) {
			return [
				'name' => $type,
				'label' => $type->label()
			];
		});
	}
}
