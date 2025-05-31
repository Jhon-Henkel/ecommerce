<?php

namespace App\Modules\Cart\CartItem\Controller;

use App\Infra\Controller\Create\BaseCreateController;
use App\Infra\UseCase\Create\ICreateUseCase;
use App\Modules\Cart\CartItem\UseCase\CartItemCreateUseCase;

class CartItemCreateController extends BaseCreateController
{
    public function __construct(protected CartItemCreateUseCase $useCase)
    {
    }

    protected function getUseCase(): ICreateUseCase
    {
        return $this->useCase;
    }

    protected function getRules(): array
    {
        return [
            'product_id' => 'required|integer|exists:App\Models\Product\Product,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
