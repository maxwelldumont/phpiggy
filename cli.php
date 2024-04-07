<?php

use Framework\Database;

include __DIR__ . "/src/Framework/Database.php";

$driver = 'mysql';
$config = [
  'host' => 'localhost',
  'port' => 3306,
  'dbname' => 'phpiggy'
];

$username = "root";
$password = "onepiece";

$db = new Database($driver, $config, $username, $password);

$productName = "Hats' or 1=1 --";

$query = "Select * from products where name = ?;";


$stmt = $db->connection->prepare($query);

$stmt->execute([$productName]);

var_dump($stmt->fetchAll(PDO::FETCH_OBJ));
