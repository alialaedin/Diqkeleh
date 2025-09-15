<?php

namespace Modules\Core\Exceptions;

use Exception;
use Illuminate\Http\Request;

class ModelCannotBeDeletedException extends Exception
{
	public function render(Request $request)
	{
		$code = $this->getCode();
		if ($code == 0) {
			$code = 409;
		}

		if ($request->wantsJson()) {
			return response()->error($this->getMessage(), $code);
		}

		return redirect()->back()->with('error', $this->getMessage());
	}
}
