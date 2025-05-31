<?php

namespace App\Modules\Cart\Controller;

use App\Infra\Controller\Update\BaseUpdateController;
use App\Infra\UseCase\Update\IUpdateUseCase;
use App\Modules\Cart\UseCase\CartUpdateUseCase;

class CartUpdateController extends BaseUpdateController
{
    public function __construct(protected CartUpdateUseCase $useCase)
    {
    }

    protected function getUseCase(): IUpdateUseCase
    {
        return $this->useCase;
    }

    protected function getRules(): array
    {
        return [
            'payment_method_id' => 'required|integer|exists:App\Models\PaymentMethod\PaymentMethod,id',
            'installments' => 'required|integer|min:1',
        ];
    }
}
