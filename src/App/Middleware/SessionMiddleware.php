<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\SessionException;
use Framework\Contracts\MiddlewareInterface;

class SessionMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    if (session_status() === PHP_SESSION_ACTIVE) {
      throw new SessionException("Session already active.");
    }

    if (headers_sent($filename, $line)) {
      throw new SessionException("Headers already sent. Consider enabling output buffering. Data outputted from $filename - Line: $line");
    }
    session_start(); //enables sessions

    $next();

    //By closing the session early, memory is freed so that it can be allocated for other requests.
    session_write_close();
  }
}
