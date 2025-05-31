<?php

namespace App\Modules\Cart\CartItem\UseCase;

use App\Infra\UseCase\Delete\IDeleteUseCase;
use App\Models\Cart\CartItem;

class CartItemDeleteUseCase implements IDeleteUseCase
{
    public function execute(int $id): void
    {
        $item = CartItem::where('id', $id)->firstOrFail();
        $cart = $item->cart;
        $item->delete();

        $cart->refresh();

        if ($cart->items->count() === 0) {
            $cart->delete();
        }
    }
}
