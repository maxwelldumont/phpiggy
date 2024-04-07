<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;

use Framework\Exceptions\ValidationException;

class Validator
{
  private array $rules = [];

  public function addRule(string $alias, RuleInterface $rule)
  {
    $this->rules[$alias] = $rule;
  }

  public function validate(array $formData, array $fields)
  {
    foreach ($fields as $fieldName => $rules) {
      foreach ($rules as $rule) {

        $ruleParams = [];

        if (str_contains($rule, ':')) {
          [$rule, $ruleParams] = explode(':', $rule);
          $ruleParams = explode(',', $ruleParams);
        }

        $ruleValidator = $this->rules[$rule];

        if ($ruleValidator->validate($formData, $fieldName, $ruleParams)) {
          continue;
        }

        $errors[$fieldName][] = $ruleValidator->getMessage($formData, $fieldName, $ruleParams);
      }
    }

    //dd($errors);&& count($errors)
    if (isset($errors)) {
      throw new ValidationException($errors);
    }
  }
}
