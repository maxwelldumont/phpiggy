<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ValidatorService;
use Framework\TemplateEngine;


class AuthController
{
  public function __construct(
    private ValidatorService $validatorService,
    private TemplateEngine $view
  ) {
  }

  public function registerView()
  {
    echo $this->view->render('/register.php');
  }

  public function register()
  {
    $this->validatorService->validateRegister($_POST);
  }
}
