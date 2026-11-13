<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrentController;
use App\Http\Controllers\FinanceReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/currencies', [CurrentController::class, 'currentData']);
    Route::get('/categories', [CategoryController::class, 'categoryData']);

    Route::post('/wallets', [WalletController::class, 'createWallet']);
    Route::put('wallets/{walletId}', [WalletController::class, 'editWallet']);
    Route::delete('/wallets/{walletId}', [WalletController::class, 'deleteWallet']);
    Route::get('/wallets', [WalletController::class, 'allWallets']);
    Route::get('/wallets/{walletId}', [WalletController::class, 'detailWallet']);

    Route::post('/transactions', [TransactionController::class, 'createTransaction']);
    Route::delete('/transaction/{transactionId}', [TransactionController::class, 'deleteTransaction']);
    Route::get('/transactions', [TransactionController::class, 'allTransaction']);

    Route::get('/reports/summary-by-category/expense', [FinanceReportController::class, 'expense']);
    Route::get('/reports/summary-by-category/income', [FinanceReportController::class, 'income']);
});
