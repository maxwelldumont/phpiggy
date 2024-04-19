<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TransactionService;
use App\Services\ValidatorService;
use Framework\TemplateEngine;

class TransactionController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private TransactionService $transactionService
  ) {
  }

  public function createView()
  {
    echo $this->view->render("transactions/create.php");
  }

  public function createTransaction()
  {
    //echo "got here!";
    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->create($_POST);
    redirectTo('/');
  }

  public function editView(array $params)
  {
    $transaction = $this->transactionService->getUserTransaction($params['transaction']);

    if (!$transaction) {
      redirectTo('/');
    }
    echo $this->view->render(
      "transactions/edit.php",
      [
        'transaction' => $transaction
      ]
    );
  }

  public function editTransaction(array $params)
  {
    //make sure that user can edit the transaction by filtering by user_id = $_SESSION[user]!!!
    // dd($params);
    $this->validatorService->validateTransaction($_POST);
    $this->transactionService->update($_POST, $params['transaction']);
    redirectTo($_SERVER['HTTP_REFERER']);
  }

  public function deleteTransaction($params)
  {
    $this->transactionService->delete($params['transaction']);
    redirectTo($_SERVER['HTTP_REFERER']);;
  }
}