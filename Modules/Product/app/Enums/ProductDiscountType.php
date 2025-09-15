<?php

namespace Modules\Product\Enums;

use Illuminate\Support\Arr;

enum ProductDiscountType: string
{
  case FLAT = "flat";
  case PERCENTAGE = "percentage";

  public function label(): string
  {
    return match ($this) {
      self::FLAT => 'مقدار',
      self::PERCENTAGE => 'درصد',
    };
  }

  public static function getCasesWithLabel()
  {
    return Arr::map(self::cases(), function (self $discountType) {
      return [
        'name' => $discountType,
        'label' => $discountType->label()
      ];
    });
  }
}
