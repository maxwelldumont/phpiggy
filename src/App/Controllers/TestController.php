<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\DependencyOne;


class TestController
{
  public function __construct(private DependencyOne $dependencyOne)
  {
  }

  public function test()
  {
    dd($this->dependencyOne);
  }
}
