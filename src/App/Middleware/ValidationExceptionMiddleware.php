<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware
{
  public function process(callable $next)
  {
    try {
      //if old data delete it:
      unset($_SESSION['oldData']);

      $next();
    } catch (ValidationException $e) {
      $oldData = $_POST;

      $excludedFields = ['password', 'confirmPassword'];

      $formattedData = array_diff_key($oldData, array_flip($excludedFields));
      //dd($formattedData);
      $_SESSION['errors'] = $e->errors;
      $_SESSION['oldData'] = $formattedData;

      //dd($_SESSION['oldData']);
      $referer = $_SERVER['HTTP_REFERER'];
      redirectTo($referer);
    }
  }
}
