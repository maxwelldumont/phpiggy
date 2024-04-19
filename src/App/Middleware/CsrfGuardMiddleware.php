<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class CsrfGuardMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    //check if the request is a post request otherwise don't perform validation

    $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
    $validMethods = ['POST', 'PATCH', 'DELETE'];

    if (!in_array($requestMethod, $validMethods)) {
      $next();
      return;
    }

    //retrieve submitted token
    //retrieve generated token
    //compare the two
    // dd([
    //   'session' => $_SESSION['token'],
    //   'post' => $_POST['token']
    // ]);
    if ($_SESSION['token'] !== $_POST['token']) {
      redirectTo('/');
    }

    //destroy the token
    unset($_SESSION['token']);

    $next();
  }
}
