<?php

namespace App\Modules\Cart\UseCase\Actions;

use App\Models\Cart\Cart;

class CartUpdateActionUseCase
{
    public function execute(Cart $cart): void
    {
        $cart->refresh();
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
        if (! is_null($cart->payment_method_id) && $this->isAllowedToDiscount($cart)) {
            $discount = ($cart->subtotal_amount * $cart->payment_method->discount_percent) / 100;
            $cart->discount_amount = round($discount, 2);
            return;
        }

        $cart->discount_amount = 0;
    }

    protected function calculateTotalAmount(Cart $cart): void
    {
        if (is_null($cart->payment_method_id) || $this->isAllowedToDiscount($cart)) {
            $cart->total_amount = round($cart->subtotal_amount - $cart->discount_amount, 2);
            return;
        }

        $fee = $cart->payment_method->fee_percent / 100;
        $total = $cart->subtotal_amount * pow(1 + $fee, $cart->installments);
        $cart->total_amount = round($total, 2);
    }

    protected function isAllowedToDiscount(Cart $cart): bool
    {
        return $cart->installments > 0 && $cart->installments <= $cart->payment_method->max_discount_installments;
    }
}
