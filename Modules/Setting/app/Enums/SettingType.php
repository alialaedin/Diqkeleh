<?php

namespace Modules\Setting\Enums;

enum SettingType: string
{
  case TEXT = 'text';
  case BOOLEAN = 'boolean';
  case EMAIL = 'email';
  case TEXTAREA = 'textarea';
  case NUMBER = 'number';
  case IMAGE = 'image';
  case VIDEO = 'video';
  case DATE = 'date';
  case TIME = 'time';
  case EDITOR = 'editor';
  case PRICE = 'price';

  public static function getFileTypes(): array
  {
    return [
      self::IMAGE,
      self::VIDEO
    ];
  }
}
