<?php

namespace App\Modules\Cart\Controller;

use App\Infra\Controller\Delete\BaseDeleteController;
use App\Infra\UseCase\Delete\IDeleteUseCase;
use App\Modules\Cart\UseCase\CartDeleteUseCase;

class CartDeleteController extends BaseDeleteController
{
    public function __construct(protected CartDeleteUseCase $useCase)
    {
    }

    protected function getUseCase(): IDeleteUseCase
    {
        return $this->useCase;
    }
}
