<?php

namespace App\Modules\Cart\CartItem\Controller;

use App\Infra\Controller\Delete\BaseDeleteController;
use App\Infra\UseCase\Delete\IDeleteUseCase;
use App\Modules\Cart\CartItem\UseCase\CartItemDeleteUseCase;

class CartItemDeleteController extends BaseDeleteController
{
    public function __construct(protected CartItemDeleteUseCase $useCase)
    {
    }

    protected function getUseCase(): IDeleteUseCase
    {
        return $this->useCase;
    }
}
