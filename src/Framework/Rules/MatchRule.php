<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class MatchRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    $fieldOne = $data[$field];
    $filedTwo = $data[$params[0]];
    return $fieldOne === $filedTwo;
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Value does not match value in field '{$params[0]}'";
  }
}
