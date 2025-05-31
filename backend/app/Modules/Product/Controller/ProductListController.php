<?php

namespace App\Modules\Product\Controller;

use App\Infra\Controller\Read\BaseListController;
use App\Infra\UseCase\Read\IListUseCase;
use App\Modules\Product\UseCase\ProductListUseCase;

class ProductListController extends BaseListController
{
    public function __construct(protected ProductListUseCase $useCase)
    {
    }

    protected function getUseCase(): IListUseCase
    {
        return $this->useCase;
    }
}
