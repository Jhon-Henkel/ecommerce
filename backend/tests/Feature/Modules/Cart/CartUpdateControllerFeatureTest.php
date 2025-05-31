<?php

namespace Feature\Modules\Cart;

use App\Infra\Response\Enum\StatusCodeEnum;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Product\Product;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\FeatureTestCase;

class CartUpdateControllerFeatureTest extends FeatureTestCase
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

    #[TestDox("Testando a atualização com dados válidos")]
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

        $this->assertDatabaseHas('cart', [
            'user_id' => $this->user->id,
            'installments' => 0,
            'total_amount' => 5750.5,
            'discount_amount' => 0,
            'subtotal_amount' => 5750.5,
            'payment_method_id' => null,
            'total_items' => 1,
        ]);

        $this->user->refresh();

        $this->assertDatabaseHas('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $paymentMethod = PaymentMethod::where('name', 'Pix')->first();

        $response = $this->putJson('/api/v1/cart/' . $this->user->cart->id, [
            'payment_method_id' => $paymentMethod->id,
            'installments' => 1,
        ], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpOk->value);

        $this->assertDatabaseHas('cart', [
            'id' => $this->user->cart->id,
            'user_id' => $this->user->id,
            'payment_method_id' => $paymentMethod->id,
            'installments' => 1,
        ]);
    }

    #[TestDox("Testando a atualização com total de parcela não permitido")]
    public function testRouteTestTwo()
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

        $this->assertDatabaseHas('cart', [
            'user_id' => $this->user->id,
            'installments' => 0,
            'total_amount' => 5750.5,
            'discount_amount' => 0,
            'subtotal_amount' => 5750.5,
            'payment_method_id' => null,
            'total_items' => 1,
        ]);

        $this->user->refresh();

        $this->assertDatabaseHas('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $paymentMethod = PaymentMethod::where('name', 'Pix')->first();

        $response = $this->putJson('/api/v1/cart/' . $this->user->cart->id, [
            'payment_method_id' => $paymentMethod->id,
            'installments' => 10,
        ], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpBadRequest->value);
        $this->assertEquals(StatusCodeEnum::HttpBadRequest->value, $response->json('status'));
        $this->assertEquals('Quantidade de parcelas não pode ser maior que o máximo permitido pelo método de pagamento selecionado.', $response->json('data'));
    }
}
