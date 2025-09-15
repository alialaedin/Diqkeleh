<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Arr;

enum ProductStatus: string
{
	case DRAFT = "draft";
	case AVAILABLE = "available";
	case OUT_OF_STOCK = "out_of_stock";

	public function label(): string
	{
		return match ($this) {
			self::DRAFT => 'پیش نویس',
			self::AVAILABLE => 'موجود',
			self::OUT_OF_STOCK => 'ناموجود',
		};
	}

	public function color(): string
	{
		return match ($this) {
			self::DRAFT => 'primary',
			self::AVAILABLE => 'success',
			self::OUT_OF_STOCK => 'danger',
		};
	}

	public static function getCasesWithLabel()
	{
		return Arr::map(self::cases(), function (self $status) {
			return [
				'name' => $status,
				'label' => $status->label()
			];
		});
	}
}
