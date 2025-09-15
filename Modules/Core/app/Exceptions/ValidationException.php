<?php

namespace Modules\Core\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as BaseValidationException;

class ValidationException extends Exception
{
  protected string $key;
  protected string $errorBag;

  public function __construct(
    string $message = 'Validation error occurred',
    string $key = 'unknown',
    string $errorBag = 'default'
  ) {
    parent::__construct($message);
    $this->key = $key;
    $this->errorBag = $errorBag;
  }

  public function render(Request $request)
  {
    $message = $this->getMessage();
    if ($request->wantsJson()) {
      throw new HttpResponseException(response()->error(
				$message,
				[
					$this->key => [$message]
				],
				422
			));
    }

    throw BaseValidationException::withMessages([
			$this->key => [$message]
		])->errorBag($this->errorBag);

  }
}
