<?php

namespace Feature\Modules\Cart;

use App\Infra\Response\Enum\StatusCodeEnum;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Product\Product;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\FeatureTestCase;

class CartGetControllerFeatureTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $cart = $this->user->cart;
        if ($cart) {
            foreach ($cart->items as $item) {
                $item->delete();
            }
            $cart->delete();
        }
    }

    protected function tearDown(): void
    {
        $cart = $this->user->cart;
        if ($cart) {
            foreach ($cart->items as $item) {
                $item->delete();
            }
            $cart->delete();
        }
        parent::tearDown();
    }

    #[TestDox("Testando com autenticação")]
    public function testRouteTestOne()
    {
        $this->assertDatabaseMissing('cart', [
            'user_id' =>  $this->user->id,
        ]);

        $product = Product::firstWhere('name', 'IPhone 16 240 GB');
        $response = $this->postJson('/api/v1/cart/item', [
            'product_id' => $product->id,
            'quantity' => 1,
        ], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpCreated->value);

        $this->user->refresh();
        $paymentMethod = PaymentMethod::where('name', 'Pix')->first();
        $response = $this->putJson('/api/v1/cart/' . $this->user->cart->id, [
            'payment_method_id' => $paymentMethod->id,
            'installments' => 1,
        ], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpOk->value);

        $response = $this->getJson('/api/v1/cart/' . $this->user->id, $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpOk->value);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'payment_method_id',
                'total_items',
                'installments',
                'subtotal_amount',
                'discount_amount',
                'total_amount',
                'items' => [
                    '*' => [
                        'id',
                        'cart_id',
                        'product_id',
                        'quantity',
                        'product' => [
                            'id',
                            'name',
                            'price',
                        ],
                    ],
                ],
                'payment_method' => [
                    'id',
                    'name',
                    'fee_percent',
                    'discount_percent',
                    'max_discount_installments',
                    'max_installments',
                ],
            ],
        ]);
    }

    #[TestDox("Testando sem autenticação")]
    public function testRouteTestTwo()
    {
        $response = $this->getJson('/api/v1/cart/1');

        $response->assertStatus(StatusCodeEnum::HttpUnauthorized->value);
    }
}
