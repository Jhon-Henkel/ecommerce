<?php

namespace Feature\Modules\Cart\CartItem\Controller;

use App\Infra\Response\Enum\StatusCodeEnum;
use App\Models\Product\Product;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\FeatureTestCase;

class CartItemDeleteControllerFeatureTest extends FeatureTestCase
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

    #[TestDox("Testando com um item no carrinho, nesse caso deve deletar o item e o carrinho")]
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

        $this->assertDatabaseHas('cart', [
            'user_id' => $this->user->id,
            'installments' => 0,
            'total_amount' => 5750.5,
            'discount_amount' => 0,
            'subtotal_amount' => 5750.5,
            'payment_method_id' => null,
            'total_items' => 1,
        ]);

        $this->assertDatabaseHas('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson('/api/v1/cart/item/' . $response->json('data.id'));

        $response->assertStatus(StatusCodeEnum::HttpNoContent->value);

        $this->assertDatabaseMissing('cart', [
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseMissing('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $product->id,
        ]);
    }

    #[TestDox("Testando adicionando mais item diferente carrinho, nesse caso deve deletar o item e recalcular o carrinho")]
    public function testRouteTestTwo()
    {
        $this->assertDatabaseMissing('cart', [
            'user_id' =>  $this->user->id,
        ]);

        $productOne = Product::firstWhere('name', 'IPhone 16 240 GB');
        $productTwo = Product::firstWhere('name', 'Playstation 5');

        $response = $this->postJson('/api/v1/cart/item', [
            'product_id' => $productOne->id,
            'quantity' => 1,
        ], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpCreated->value);

        $response = $this->postJson('/api/v1/cart/item', [
            'product_id' => $productTwo->id,
            'quantity' => 1,
        ], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpCreated->value);

        $this->user->refresh();

        $this->assertDatabaseHas('cart', [
            'user_id' => $this->user->id,
            'installments' => 0,
            'total_amount' => 10050.5,
            'discount_amount' => 0,
            'subtotal_amount' => 10050.5,
            'payment_method_id' => null,
            'total_items' => 2,
        ]);

        $this->assertDatabaseHas('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $productOne->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseHas('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $productTwo->id,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson('/api/v1/cart/item/' . $response->json('data.id'));
        $response->assertStatus(StatusCodeEnum::HttpNoContent->value);

        $this->assertDatabaseHas('cart', [
            'user_id' => $this->user->id,
            'installments' => 0,
            'total_amount' => 5750.5,
            'discount_amount' => 0,
            'subtotal_amount' => 5750.5,
            'payment_method_id' => null,
            'total_items' => 1,
        ]);

        $this->assertDatabaseHas('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $productOne->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseMissing('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $productTwo->id,
        ]);
    }

    #[TestDox("Testando com delete de item que não existe")]
    public function testRouteTestThree()
    {
        $response = $this->deleteJson('/api/v1/cart/item/999999', [], $this->makeHeaders());
        $response->assertStatus(StatusCodeEnum::HttpNoContent->value);
    }

    #[TestDox("Testando sem autenticação")]
    public function testRouteTestFour()
    {
        $response = $this->deleteJson('/api/v1/cart/item/1');

        $response->assertStatus(StatusCodeEnum::HttpUnauthorized->value);
    }
}
