<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
  public function __construct(private Database $db)
  {
  }

  public function create(array $formData)
  {
    $formattedDate = "{$formData['date']} 00:00:00";
    $this->db->query(
      "INSERT INTO transactions(user_id, description, amount, date)
      VALUES(:user_id, :description, :amount, :date);",
      [
        'user_id' => $_SESSION['user'],
        'description' => $formData['description'],
        'amount' => $formData['amount'],
        'date' => $formattedDate
      ]
    );
  }

  public function update(array $formData, string $id)
  {
    $formattedDate = "{$formData['date']} 00:00:00";
    $this->db->query(
      "UPDATE transactions
      SET description = :description, amount = :amount, date = :date
      WHERE id = {$id} AND user_id = :user_id;",
      [
        'description' => $formData['description'],
        'amount' => $formData['amount'],
        'date' => $formattedDate,
        'user_id' => $_SESSION['user']
      ]
    );
  }

  public function getUserTransactions($length, $offset, $searchTerm)
  {
    // echo $searchTerm;

    $parameters =   [
      'user_id' => $_SESSION['user'],
      'description' => "%$searchTerm%",
      'amount' => "%$searchTerm%"
    ];

    $transactions = $this->db->query(
      "SELECT id, description, amount, DATE(date) as date FROM transactions 
       WHERE user_id = :user_id and (description LIKE :description or amount LIKE :amount)
       LIMIT {$length} OFFSET {$offset};",
      $parameters
    )->findAll();

    $transactionCount = $this->db->query(
      "SELECT COUNT(*) FROM transactions 
       WHERE user_id = :user_id and (description LIKE :description or amount LIKE :amount);",
      $parameters
    )->count();

    return [$transactions, $transactionCount];
  }

  public function getUserTransaction(string $id)
  {
    return $this->db->query(
      "SELECT id, description, amount, DATE(date) as date FROM transactions
      WHERE id = :id AND user_id = :user_id",
      [
        'id' => $id,
        'user_id' => $_SESSION['user']
      ]
    )->find();
  }

  public function delete(string $id)
  {
    $this->db->query(
      "DELETE FROM transactions WHERE id = :id AND user_id = :user_id;",
      [
        'id' => $id,
        'user_id' => $_SESSION['user']
      ]
    );
  }
}
