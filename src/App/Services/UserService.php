<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use Framework\TemplateEngine;

class UserService
{
  public function __construct(private Database $db)
  {
  }

  public function isEmailTaken(string $email)
  {
    $emailCount = $this->db->query(
      "SELECT COUNT(*) FROM users WHERE email = :email;",
      ['email' => $email]
    )->count();

    if ($emailCount > 0) {
      throw new ValidationException(['email' => ['Email taken']]);
    }
  }

  public function insertUserData($userData)
  {
    $sql = "INSERT INTO users (email, password, age, country, social_media_url) values (:email, :password, :age, :country, :social_media_url);";
    $params = [
      'email' => $userData['email'],
      'password' => $userData['password'],
      'age' => $userData['age'],
      'country' => $userData['country'],
      'social_media_url' => $userData['mySocialMedia']
    ];

    $this->db->query($sql, $params);
  }
}
