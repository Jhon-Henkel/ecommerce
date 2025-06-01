<?php

namespace App\Modules\Cart\Controller;

use App\Infra\Controller\Read\BaseGetController;
use App\Infra\UseCase\Read\IGetUseCase;
use App\Modules\Cart\UseCase\CartGetUseCase;

class CartGetController extends BaseGetController
{
    public function __construct(protected CartGetUseCase $useCase)
    {
    }

    protected function getUseCase(): IGetUseCase
    {
        return $this->useCase;
    }
}
