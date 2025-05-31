<?php

namespace App\Modules\Cart\UseCase;

use App\Infra\Response\Exceptions\BadRequestException;
use App\Infra\UseCase\Update\IUpdateUseCase;
use App\Models\Cart\Cart;
use App\Models\PaymentMethod\PaymentMethod;

class CartUpdateUseCase implements IUpdateUseCase
{
    public function execute(array $data, int $id): array
    {
        $this->validateData($data);
        $item = Cart::findOrFail($id);

        $item->update([
            'payment_method_id' =>  $data['payment_method_id'],
            'installments' => $data['installments'],
        ]);

        return $item->refresh()->toArray();
    }

    protected function validateData(array $data): void
    {
        $paymentMethod = PaymentMethod::findOrFail($data['payment_method_id']);
        if ($paymentMethod->max_installments < $data['installments']) {
            throw new BadRequestException('Quantidade de parcelas não pode ser maior que o máximo permitido pelo método de pagamento selecionado.');
        }
    }
}
