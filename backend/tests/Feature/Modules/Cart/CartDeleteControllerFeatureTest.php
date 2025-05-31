<?php

namespace Feature\Modules\Cart;

use App\Infra\Response\Enum\StatusCodeEnum;
use App\Models\Product\Product;
use Tests\FeatureTestCase;

class CartDeleteControllerFeatureTest extends FeatureTestCase
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

    public function testRoute()
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

        $response = $this->deleteJson('/api/v1/cart/' . $this->user->cart->id, [], $this->makeHeaders());

        $response->assertStatus(StatusCodeEnum::HttpNoContent->value);

        $this->assertDatabaseMissing('cart', [
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseMissing('cart_item', [
            'cart_id' => $this->user->cart->id,
            'product_id' => $product->id,
        ]);
    }
}
