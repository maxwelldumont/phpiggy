<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class AuthRequiredMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    //if user isn't logged in redirect them to the login page and don't allow them to proceed to the controller
    if (empty($_SESSION['user'])) {
      redirectTo('/login');
    }
    $next();
  }
}
