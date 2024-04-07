<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database};
use App\Config\Paths;
use App\Services\ValidatorService;

return [
  TemplateEngine::class => fn () => new TemplateEngine(Paths::VIEW),
  // DependencyOne::class => fn () => $this->container->resolve(DependencyOne::class),
  // DependencyTwo::class => fn () => $this->container->resolve(DependencyTwo::class),
  ValidatorService::class => fn () => new ValidatorService(),
  Database::class => fn () =>
  new Database(
    $_ENV['DB_DRIVER'],
    [
      'host' => $_ENV['DB_HOST'],
      'port' => $_ENV['DB_PORT'],
      'dbname' => $_ENV['DB_NAME']
    ],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
  ),
];
