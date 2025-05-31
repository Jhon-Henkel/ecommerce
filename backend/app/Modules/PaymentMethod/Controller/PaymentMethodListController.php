<?php

namespace App\Modules\PaymentMethod\Controller;

use App\Infra\Controller\Read\BaseListController;
use App\Infra\UseCase\Read\IListUseCase;
use App\Modules\PaymentMethod\UseCase\PaymentMethodListUseCase;

class PaymentMethodListController extends BaseListController
{
    public function __construct(protected PaymentMethodListUseCase $useCase)
    {
    }

    protected function getUseCase(): IListUseCase
    {
        return $this->useCase;
    }
}
