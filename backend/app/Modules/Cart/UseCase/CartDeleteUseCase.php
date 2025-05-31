<?php

namespace App\Modules\Cart\UseCase;

use App\Infra\UseCase\Delete\IDeleteUseCase;
use App\Models\Cart\Cart;

class CartDeleteUseCase implements IDeleteUseCase
{
    public function execute(int $id): void
    {
        $cart = Cart::find($id);
        if (empty($cart)) {
            return;
        }
        foreach ($cart->items as $item) {
            $item->delete();
        }
        $cart->delete();
    }
}
