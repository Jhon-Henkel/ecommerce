<?php

namespace Feature\Modules\Product\Controller;

use App\Infra\Response\Enum\StatusCodeEnum;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\FeatureTestCase;

class ProductListControllerFeatureTest extends FeatureTestCase
{
    #[TestDox("Testando listagem sem filtros")]
    public function testRouteTestOne()
    {
        $response = $this->getJson('/api/v1/product');

        $response->assertStatus(StatusCodeEnum::HttpOk->value);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
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
        $response = $this->getJson('/api/v1/product?search=guitarra');

        $response->assertStatus(StatusCodeEnum::HttpOk->value);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'created_at',
                    'updated_at',
                ],
            ],
            'status',
        ]);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Guitarra Elétrica Gibson', $response->json('data')[0]['name']);
    }
}
