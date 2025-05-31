<?php

use App\Infra\Route\Enum\RouteNameEnum;
use App\Modules\Product\Controller\ProductListController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('product')->group(function () {
        Route::get('', ProductListController::class)->name(RouteNameEnum::ApiProductList);
    });
});
