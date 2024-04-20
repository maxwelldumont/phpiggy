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

  public function create(array $userData)
  {
    // dd($userData['socialMediaURL']);

    $sql = "INSERT INTO users (email, password, age, country, social_media_url,created_at, updated_at) values (:email, :password, :age, :country, :social_media_url, now(), now());";
    $params = [
      'email' => $userData['email'],
      'password' => password_hash($userData['password'], PASSWORD_BCRYPT, ['cost' => 12]),
      'age' => $userData['age'],
      'country' => $userData['country'],
      'social_media_url' => $userData['socialMediaURL']
    ];

    $this->db->query($sql, $params);
  }

  public function login(array $userData)
  {
    $user = $this->db->query("SELECT * FROM users WHERE email = :email", [
      'email' => $userData['email']
    ])->find();

    $passwordMatch = password_verify($userData['password'], $user['password'] ?? '');

    //dd($passwordMatch);

    if (!$user || !$passwordMatch) {
      throw new ValidationException(['password' => ['Invalid credentials']]);
    }

    session_regenerate_id();
    $_SESSION['user'] = $user['id'];
  }

  public function logout()
  {
    unset($_SESSION['user']);

    session_regenerate_id();
  }
}
