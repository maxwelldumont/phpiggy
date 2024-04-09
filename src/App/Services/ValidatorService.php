<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Rules\{
  RequiredRule,
  EmailRule,
  InRule,
  MinRule,
  UrlRule,
  MatchRule
};
use Framework\Validator;

class ValidatorService
{
  private Validator $validator;

  public function __construct()
  {
    $this->validator = new Validator();

    $this->validator->addRule('required', new RequiredRule());
    $this->validator->addRule('email', new EmailRule());
    $this->validator->addRule('min', new MinRule());
    $this->validator->addRule('in', new InRule());
    $this->validator->addRule('url', new UrlRule());
    $this->validator->addRule('match', new MatchRule());
  }

  public function validateRegister(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => ['required', 'email'],
      'age' => ['required', 'min:18'], //rule:rule_parameters
      'country' => ['required', 'in:USA,Canada'],
      'socialMediaURL' => ['required', 'url'],
      'password' => ['required'],
      'confirmPassword' => ['required', 'match:password'],
      'tos' => ['required'],
    ]);
  }

  public function validateLogin(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);
  }
}
