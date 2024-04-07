<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException;

class Database
{
  protected PDO $connection;

  public function __construct(string $driver, array $config, string $username, string $password)
  {
    $dsnConfig = http_build_query(data: $config, arg_separator: ';');
    $dsn = "{$driver}:{$dsnConfig}";

    try {
      $this->connection = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
      die("Unable to connect to database");
    }
  }

  public function query(string $query)
  {
    $this->connection->query($query);
  }
}
