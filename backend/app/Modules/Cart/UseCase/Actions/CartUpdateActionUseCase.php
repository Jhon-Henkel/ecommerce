<?php

namespace App\Modules\Cart\UseCase\Actions;

use App\Models\Cart\Cart;

class CartUpdateActionUseCase
{
    public function execute(Cart $cart): void
    {
        $this->calculateSubTotalAmount($cart);
        $cart->total_items = $cart->items()->sum('quantity');
        $this->calculateDiscountAmount($cart);
        $this->calculateTotalAmount($cart);
        $cart->update();
    }

    protected function calculateSubTotalAmount(Cart $cart): void
    {
        $subtotal = 0;
        foreach ($cart->items as $cartItem) {
            $subtotal += $cartItem->product->price * $cartItem->quantity;
        }
        $cart->subtotal_amount = $subtotal;
    }

    protected function calculateDiscountAmount(Cart $cart): void
    {
        // todo - adicionar lógica de desconto
        $cart->discount_amount = 0;
    }

    protected function calculateTotalAmount(Cart $cart): void
    {
        // todo - adicionar lógica de calculo do total, considerando descontos e taxas
        $cart->total_amount = $cart->subtotal_amount;
    }
}
