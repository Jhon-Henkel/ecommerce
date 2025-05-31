<?php

namespace Feature\Modules\PaymentMethod\Controller;

use App\Infra\Response\Enum\StatusCodeEnum;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\FeatureTestCase;

class PaymentMethodListControllerFeatureTest extends FeatureTestCase
{
    #[TestDox("Testando listagem sem filtros")]
    public function testRouteTestOne()
    {
        $response = $this->getJson('/api/v1/payment-method');

        $response->assertStatus(StatusCodeEnum::HttpOk->value);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'fee_percent',
                    'discount_percent',
                    'max_discount_installments',
                    'max_installments',
                    'created_at',
                    'updated_at',
                ],
            ],
            'status',
        ]);
    }

    #[TestDox("Testando listagem filtrando por nome")]
    public function testRouteTestTwo()
    {
        $response = $this->getJson('/api/v1/payment-method?search=pix');

        $response->assertStatus(StatusCodeEnum::HttpOk->value);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'fee_percent',
                    'discount_percent',
                    'max_discount_installments',
                    'max_installments',
                    'created_at',
                    'updated_at',
                ],
            ],
            'status',
        ]);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Pix', $response->json('data')[0]['name']);
    }
}
