<?php

use App\Infra\Route\Enum\RouteNameEnum;
use App\Modules\Auth\Controller\Login\LoginController;
use App\Modules\PaymentMethod\Controller\PaymentMethodListController;
use App\Modules\Product\Controller\ProductListController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class)->name(RouteNameEnum::ApiAuthLogin);
});

// Rotas sem autenticação
Route::prefix('v1')->group(function () {
    Route::prefix('product')->group(function () {
        Route::get('', ProductListController::class)->name(RouteNameEnum::ApiProductList);
    });

    Route::prefix('payment-method')->group(function () {
        Route::get('', PaymentMethodListController::class)->name(RouteNameEnum::ApiPaymentMethodList);
    });
});
