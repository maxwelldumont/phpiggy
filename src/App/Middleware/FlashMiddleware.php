<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class FlashMiddleware implements MiddlewareInterface
{
  public function __construct(private TemplateEngine $view)
  {
  }

  public function process(callable $next)
  {
    $this->view->addGlobal('errors', $_SESSION['errors'] ?? []); //expose errors to template
    $this->view->addGlobal('oldData', $_SESSION['oldData'] ?? []); //expose old data to template
    unset($_SESSION['errors']);
    $next();
  }
}
