<?php

declare(strict_types=1);

require __DIR__ . "/vendor/autoload.php";

use App\Config\Paths;
use Framework\Database;
use Dotenv\Dotenv;


include __DIR__ . "/src/Framework/Database.php";

$dotEnv = Dotenv::createImmutable(Paths::ROOT);
$dotEnv->load();

$driver = $_ENV['DB_DRIVER'];
$config = [
  'host' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'dbname' => $_ENV['DB_NAME']
];

$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

$db = new Database($driver, $config, $username, $password);

$sqlFile = file_get_contents('./database.sql');

$db->query($sqlFile);
