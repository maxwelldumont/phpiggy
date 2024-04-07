<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Config\Paths;
use Dotenv\Dotenv;

use function App\Config\{registerMiddleware, registerRoutes};

// echo "\nPaths: " . Paths::ROOT;

$dotEnv = Dotenv::createImmutable(Paths::ROOT);
$dotEnv->load();

$app = new App(Paths::SOURCE . "app/container-definitions.php");

registerRoutes($app);

registerMiddleware($app);

return $app;
