<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ReceiptService;
use App\Services\TransactionService;
use App\Services\ValidatorService;
use Framework\TemplateEngine;

class TransactionController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private TransactionService $transactionService,
    private ReceiptService $receiptService
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
    redirectTo('/');
  }

  public function deleteTransaction($params)
  {
    $this->transactionService->delete($params['transaction']);

    $receipt = $this->receiptService->getReceipts($params['transaction']);
    if (empty($receipt)) {
      redirectTo("/");
    }
    $this->receiptService->delete($receipt);
    redirectTo($_SERVER['HTTP_REFERER']);;
  }
}
