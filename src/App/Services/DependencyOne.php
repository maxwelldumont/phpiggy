<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\DependencyTwo;

class DependencyOne
{
  public function __construct(private DependencyTwo $dependencyTne)
  {
  }
}
