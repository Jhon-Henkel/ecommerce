<?php

namespace App\Modules\Cart\CartItem\UseCase;

use App\Infra\UseCase\Create\ICreateUseCase;
use App\Models\Cart\Cart;
use App\Models\Cart\CartItem;

class CartItemCreateUseCase implements ICreateUseCase
{
    public function execute(array $data): array
    {
        $cart = $this->getCart();
        $item = CartItem::create([
            'cart_id' =>  $cart->id,
            'product_id' =>  $data['product_id'],
            'quantity' =>  $data['quantity'],
        ]);
        return $item->toArray();
    }

    protected function getCart(): Cart
    {
        $cart = auth()->user()->cart;
        if (empty($cart)) {
            $cart = Cart::create([
                'user_id' =>  auth()->user()->id,
                'installments' => 0
            ]);
        }
        return $cart;
    }
}
