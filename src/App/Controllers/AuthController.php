<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{ValidatorService, UserService};
use Framework\TemplateEngine;


class AuthController
{
  public function __construct(
    private ValidatorService $validatorService,
    private TemplateEngine $view,
    private UserService $userService
  ) {
  }

  public function registerView()
  {
    echo $this->view->render('/register.php');
  }

  public function register()
  {
    $this->validatorService->validateRegister($_POST);
    $this->userService->isEmailTaken($_POST['email']);
    $this->userService->create($_POST);

    $this->userService->login($_POST);
    redirectTo('/');
    // redirectTo('/');
  }

  public function loginView()
  {
    // echo"got here!";
    echo $this->view->render('/login.php');
  }

  public function login()
  {
    //echo "got here!";
    $this->validatorService->validateLogin($_POST);
    $this->userService->login($_POST);
    redirectTo('/');
  }

  public function logout()
  {
    $this->userService->logout();
    redirectTo('/login');
  }
}
