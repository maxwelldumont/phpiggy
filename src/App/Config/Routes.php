<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\AuthController;
// use App\Controllers\TestController;
use App\Controllers\{
  HomeController,
  AboutController,
  ReceiptController,
  TransactionController
};
use App\Middleware\{
  AuthRequiredMiddleware,
  GuestOnlyMiddleware
};

function  registerRoutes(App $app)
{
  $app->get('/', [HomeController::class, 'home'])->add(AuthRequiredMiddleware::class);
  $app->get('/about', [AboutController::class, 'about']);
  // $app->get('/test', [TestController::class, 'test']);
  $app->get('/register', [AuthController::class, 'registerView'])->add(GuestOnlyMiddleware::class);
  $app->post('/register', [AuthController::class, 'register'])->add(GuestOnlyMiddleware::class);
  $app->get('/login', [AuthController::class, 'loginView'])->add(GuestOnlyMiddleware::class);
  $app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
  $app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
  $app->get('/transaction', [TransactionController::class, 'createView'])->add(AuthRequiredMiddleware::class);
  $app->post('/transaction', [TransactionController::class, 'createTransaction'])->add(AuthRequiredMiddleware::class);
  $app->get('/transaction/{transaction}', [TransactionController::class, 'editView'])->add(AuthRequiredMiddleware::class);
  $app->post('/transaction/{transaction}', [TransactionController::class, 'editTransaction'])->add(AuthRequiredMiddleware::class);
  $app->delete('/transaction/{transaction}', [TransactionController::class, 'deleteTransaction'])->add(AuthRequiredMiddleware::class);
  $app->get('/transaction/{transaction}/receipt', [ReceiptController::class, 'uploadView'])->add(AuthRequiredMiddleware::class);
  $app->post('/transaction/{transaction}/receipt', [ReceiptController::class, 'upload'])->add(AuthRequiredMiddleware::class);
  $app->get('/transaction/{transaction}/receipt/{receipt}', [ReceiptController::class, 'download'])->add(AuthRequiredMiddleware::class);
  $app->post('/transaction/{transaction}/receipt/{receipt}', [ReceiptController::class, 'delete'])->add(AuthRequiredMiddleware::class);
}
