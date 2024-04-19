<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class GuestOnlyMiddleware implements MiddlewareInterface
{
  //if a user is authenticated they shouldn't be able to access the login page, until logged out
  public function process(callable $next)
  {
    // echo "session: \n";
    // dd($_SESSION['user']);
    if (!empty($_SESSION['user'])) {
      redirectTo('/');
    }

    $next();
  }
}
