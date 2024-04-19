<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\TransactionService;

class HomeController
{
  public function __construct(
    private TemplateEngine $view,
    private TransactionService $transactionService
  ) {
  }

  public function home()
  {
    $page = $_GET['p'] ?? 1;
    $page = (int) $page;
    $length = 3;
    $offset = ($page - 1) * $length;
    // $searchTerm = $_GET['s'] ?? null;

    $searchTerm = addcslashes($_GET['s'] ?? '', '%_');

    [$transactions, $count] = $this->transactionService->getUserTransactions($length, $offset, $searchTerm);

    // echo "\noffset: {$offset} \n";
    // echo "\currentPage: {$page} \n";
    // echo "\count: {$count} \n";

    $lastPage = ceil($count / $length);

    $pageLinks = array_map(
      fn ($val) => http_build_query([
        'p' => $val,
        's' => $searchTerm
      ]),
      range(1, $lastPage)
    );

    // dd([$pageLinks, 'lastPage' => $lastPage]);

    // dd($transactions);
    echo $this->view->render('/index.php', [
      'transactions' => $transactions,
      'currentPage' => $page,
      'previousPageQuery' => http_build_query([
        'p' => $page - 1,
        's' => $searchTerm
      ]),
      'nextPageQuery' => http_build_query([
        'p' => $page + 1,
        's' => $searchTerm
      ]),
      'lastPage' => $lastPage,
      'pageLinks' => $pageLinks,
      'searchTerm' => $searchTerm
    ]);
  }
}
