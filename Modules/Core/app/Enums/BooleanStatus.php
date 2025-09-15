<?php

namespace Modules\Core\Enums;

use Illuminate\Support\Arr;

enum BooleanStatus: int
{
  case TRUE = 1;
  case FALSE = 0;

  public function label(): string
  {
    return match ($this) {
      self::TRUE => 'فعال',
      self::FALSE => 'غیر فعال',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::TRUE => 'success',
      self::FALSE => 'danger',
    };
  }

  public static function getCasesWithLabel()
  {
    return Arr::map(self::cases(), function (self $status) {
      return [
        'value' => $status->value,
        'label' => $status->label()
      ];
    });
  }
}
