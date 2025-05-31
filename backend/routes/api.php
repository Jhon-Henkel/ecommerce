<?php

use App\Infra\Route\Enum\RouteNameEnum;
use App\Modules\Auth\Controller\Login\LoginController;
use App\Modules\Cart\CartItem\Controller\CartItemCreateController;
use App\Modules\Cart\CartItem\Controller\CartItemDeleteController;
use App\Modules\Cart\CartItem\Controller\CartItemUpdateController;
use App\Modules\Cart\Controller\CartDeleteController;
use App\Modules\Cart\Controller\CartUpdateController;
use App\Modules\PaymentMethod\Controller\PaymentMethodListController;
use App\Modules\Product\Controller\ProductListController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class)->name(RouteNameEnum::ApiAuthLogin);
});

Route::prefix('v1')->group(function () {
    Route::prefix('product')->group(function () {
        Route::get('', ProductListController::class)->name(RouteNameEnum::ApiProductList);
    });

    Route::prefix('payment-method')->group(function () {
        Route::get('', PaymentMethodListController::class)->name(RouteNameEnum::ApiPaymentMethodList);
    });

    // Rotas com autenticação
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::prefix('cart')->group(function () {
            Route::prefix('item')->group(function () {
                Route::post('', CartItemCreateController::class)->name(RouteNameEnum::ApiCartItemCreate);
                Route::put('{id}', CartItemUpdateController::class)->name(RouteNameEnum::ApiCartItemUpdate);
                Route::delete('{id}', CartItemDeleteController::class)->name(RouteNameEnum::ApiCartItemDelete);
            });
            Route::put('{id}', CartUpdateController::class)->name(RouteNameEnum::ApiCartItemUpdate);
            Route::delete('{id}', CartDeleteController::class)->name(RouteNameEnum::ApiCartItemDelete);
        });
    });
});


