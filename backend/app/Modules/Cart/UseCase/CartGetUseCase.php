<?php

namespace App\Modules\Cart\UseCase;

use App\Infra\UseCase\Read\IGetUseCase;
use App\Models\Cart\Cart;

class CartGetUseCase implements IGetUseCase
{
    public function execute(int $id): array
    {
        return Cart::with('payment_method', 'items.product')->findOrFail($id)->toArray();
    }
}
