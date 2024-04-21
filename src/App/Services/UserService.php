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
    // unset($_SESSION['user']); //used in cases where for example we want to continue recording user data after they log out
    //instead destroy all session data
    session_destroy();

    // session_regenerate_id(); //assigns a new cookie id but does not destroy it
    //to destroy a cookie we must reset the expiration date to now(). this will case the browser to drop the old cookie;
    $params = session_get_cookie_params();
    setcookie(
      'PHPSESSID',
      '', //how this is the l
      time() - 3600, //get current time - 3600 seconds to ensure cookie has expired
      $params['path'],
      $params['domain'],
      $params['secure'],
      $params['httponly']
    );
  }
}
