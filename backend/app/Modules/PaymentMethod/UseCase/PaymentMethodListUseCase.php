<?php

namespace App\Modules\PaymentMethod\UseCase;

use App\Infra\UseCase\Read\IListUseCase;
use App\Models\PaymentMethod\PaymentMethod;

class PaymentMethodListUseCase implements IListUseCase
{
    public function execute(int $perPage, int $page, string $search, string $orderBy, string $orderByDirection, ?array $queryParams = null): array
    {
        $data = PaymentMethod::query()->select();

        if (!empty($search)) {
            $data->where(function ($query) use ($search) {
                $query->whereRaw("name LIKE ? COLLATE NOCASE", ["%$search%"]);
            });
        }

        return $data->orderBy($orderBy, $orderByDirection)->paginate($perPage, ['*'], 'page', $page)->toArray();
    }
}
