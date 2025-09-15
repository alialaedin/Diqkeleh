<?php

namespace Modules\Core\Helpers;

class Helpers
{
	public static function getModelIdFromUrl(string $model)
	{
		$model = request()->route($model);
		return is_object($model) ? $model->getKey() : $model;
	}

	public static function removeComma(?string $string)
	{
		return $string ? (int) str_replace(',', '', $string) : null;
	}
}
