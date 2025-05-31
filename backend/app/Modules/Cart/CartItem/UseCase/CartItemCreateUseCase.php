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
        $item = $this->findItem($cart, $data['product_id']);

        if (is_null($item)) {
            $item = CartItem::create([
                'cart_id' =>  $cart->id,
                'product_id' =>  $data['product_id'],
                'quantity' =>  $data['quantity'],
            ]);
        } else {
            $item->quantity += $data['quantity'];
            $item->update();
        }

        return $item->toArray();
    }

    protected function getCart(): Cart
    {
        $cart = Cart::firstWhere('user_id', auth()->user()->id);
        if (empty($cart)) {
            $cart = Cart::create([
                'user_id' =>  auth()->user()->id,
                'installments' => 0
            ]);
        }
        return $cart;
    }

    protected function findItem(Cart $cart, int $productId): CartItem|null
    {
        return CartItem::query()
            ->where('product_id', $productId)
            ->where('cart_id', $cart->id)
            ->first();
    }
}
