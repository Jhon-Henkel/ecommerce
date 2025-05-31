<?php

namespace Feature\Modules\Cart;

use App\Infra\Response\Enum\StatusCodeEnum;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Product\Product;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[TestDox("Testando a atualização com dados válidos com forma de pagamento sendo Pix")]
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
            'total_amount' => 5175.45,
            'subtotal_amount' => 5750.5,
            'discount_amount' => 575.05,
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

    #[TestDox("Testando a atualização com dados válidos com forma de pagamento sendo Cartão de Crédito")]
    #[DataProvider("routeTestThreeDataProvider")]
    public function testRouteTestThree(int $installments, float $totalAmount, float $discountAmount)
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

        $paymentMethod = PaymentMethod::where('name', 'Cartão de Crédito')->first();

        $response = $this->putJson('/api/v1/cart/' . $this->user->cart->id, [
            'payment_method_id' => $paymentMethod->id,
            'installments' => $installments,
        ], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpOk->value);

        $this->assertDatabaseHas('cart', [
            'id' => $this->user->cart->id,
            'user_id' => $this->user->id,
            'payment_method_id' => $paymentMethod->id,
            'installments' => $installments,
            'total_amount' => $totalAmount,
            'subtotal_amount' => 5750.5,
            'discount_amount' => $discountAmount,
        ]);
    }

    public static function routeTestThreeDataProvider(): array
    {
        return [
            'Teste com 1 parcela' => [1, 5175.45, 575.05],
            'Teste com 2 parcela' => [2, 5866.09, 0],
            'Teste com 3 parcela' => [3, 5924.75, 0],
            'Teste com 4 parcela' => [4, 5983.99, 0],
            'Teste com 5 parcela' => [5, 6043.83, 0],
            'Teste com 6 parcela' => [6, 6104.27, 0],
            'Teste com 7 parcela' => [7, 6165.31, 0],
            'Teste com 8 parcela' => [8, 6226.97, 0],
            'Teste com 9 parcela' => [9, 6289.24, 0],
            'Teste com 10 parcela' => [10, 6352.13, 0],
            'Teste com 11 parcela' => [11, 6415.65, 0],
            'Teste com 12 parcela' => [12, 6479.81, 0],
        ];
    }
}
