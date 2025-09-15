<?php

namespace Modules\Courier\Enums;

use Illuminate\Support\Arr;

enum CourierType: string
{
  case MOTOR = 'motor';
  case CAR = 'car';

  public function label(): string
  {
    return match ($this) {
      self::MOTOR => 'پیک موتوری',
      self::CAR => 'پیک ماشینی',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::MOTOR => 'primary',
      self::CAR => 'secondary',
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
