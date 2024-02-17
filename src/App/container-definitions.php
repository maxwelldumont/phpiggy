<?php

declare(strict_types=1);

use Framework\TemplateEngine;
use App\Config\Paths;
use App\Services\DependencyOne;
use App\Services\DependencyTwo;

return [
  TemplateEngine::class => fn () => new TemplateEngine(Paths::VIEW),
  DependencyOne::class => fn () => $this->container->resolve(DependencyOne::class),
  DependencyTwo::class => fn () => $this->container->resolve(DependencyTwo::class),
];
