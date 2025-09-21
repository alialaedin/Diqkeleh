<?php

namespace Modules\Core\Helpers;

use Hekmatinasser\Verta\Facades\Verta;

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

	public static function toGregorian(string $jDate): ?string
	{
		$jDateArray = explode('-', $jDate);
		$dateArray = Verta::jalaliToGregorian(
			(int)$jDateArray[0],
			(int)$jDateArray[1],
			(int)$jDateArray[2]
		);
		$output = implode('-', $dateArray);

		return $output;
	}
}
