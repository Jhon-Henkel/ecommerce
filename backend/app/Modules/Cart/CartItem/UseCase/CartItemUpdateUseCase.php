<?php

namespace App\Modules\Cart\CartItem\UseCase;

use App\Infra\UseCase\Update\IUpdateUseCase;
use App\Models\Cart\CartItem;

class CartItemUpdateUseCase implements IUpdateUseCase
{
    public function execute(array $data, int $id): array
    {
        $item = CartItem::where('id', $id)->firstOrFail();
        $item->quantity = $data['quantity'];
        $item->update();

        return $item->toArray();
    }
}
