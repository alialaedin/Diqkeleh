<?php

namespace Modules\Order\Enums;

use Illuminate\Support\Arr;

enum OrderStatus: string
{
  case NEW = 'new';
  case CANCELED = 'canceled';
  case DELIVERED = 'delivered';

  public function label(): string
  {
    return match ($this) {
      self::NEW => 'جدید',
      self::CANCELED => 'کنسل شده',
      self::DELIVERED => 'ارسال شده',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::NEW => 'primary',
      self::DELIVERED => 'success',
      self::CANCELED => 'danger',
    };
  }

  public static function getCasesWithLabel(): array
  {
    return Arr::map(self::cases(), function (self $status) {
      return [
        'name' => $status->value,
        'label' => $status->label()
      ];
    });
  }
}
