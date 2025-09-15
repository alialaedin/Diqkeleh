<?php

namespace Modules\Activity\Enums;

enum EventType: string
{
  case UPDATE = 'update';
  case CREATE = 'create';
  case DELETE = 'delete';
}
