<?php

namespace App\Modules\Cart\CartItem\Controller;

use App\Infra\Controller\Update\BaseUpdateController;
use App\Infra\UseCase\Update\IUpdateUseCase;
use App\Modules\Cart\CartItem\UseCase\CartItemUpdateUseCase;

class CartItemUpdateController extends BaseUpdateController
{
    public function __construct(protected CartItemUpdateUseCase $useCase)
    {
    }

    protected function getUseCase(): IUpdateUseCase
    {
        return $this->useCase;
    }

    protected function getRules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
        ];
    }
}
