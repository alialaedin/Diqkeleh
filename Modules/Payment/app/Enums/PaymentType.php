<?php

namespace Modules\Payment\Enums;

use Illuminate\Support\Arr;

enum PaymentType: string
{
  case POS = 'pos';
  case CARD_BY_CARD = 'card_by_card';
  case CASH = 'cash';
  case WALLET = 'wallet';

  public function label(): string
  {
    return match ($this) {
      self::POS => 'پوز',
      self::CARD_BY_CARD => 'کارت به کارت',
      self::CASH => 'نقدی',
      self::WALLET => 'کیف پول',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::POS => 'primary',
      self::CARD_BY_CARD => 'success',
      self::CASH => 'secondary',
      self::WALLET => 'danger',
    };
  }

  public static function getCasesWithLabel(): array
  {
    return Arr::map(self::cases(), function (self $type) {
      return [
        'name' => $type->value,
        'label' => $type->label()
      ];
    });
  }

  public static function getTypeByRequestKey(string $key)
  {
    return match ($key) {
      'pos_amount' => self::POS,
      'card_by_card_amount' => self::CARD_BY_CARD,
      'cash_amount' => self::CASH,
      'from_wallet_amount' => self::WALLET,
    };
  }
}
