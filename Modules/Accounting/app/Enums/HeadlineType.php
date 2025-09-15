<?php

namespace Modules\Accounting\Enums;

use Illuminate\Support\Arr;

enum HeadlineType: string
{
  case REVENUE = 'revenue';
  case EXPENSE = 'expense';

  public function label(): string
  {
    return match ($this) {
      self::REVENUE => 'درآمد',
      self::EXPENSE => 'هزینه',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::REVENUE => 'info',
      self::EXPENSE => 'warning',
    };
  }

  public static function getCasesWithLabel()
  {
    return Arr::map(self::cases(), function (self $type) {
      return [
        'name' => $type->value,
        'label' => $type->label()
      ];
    });
  }
}
