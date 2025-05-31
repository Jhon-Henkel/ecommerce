<?php

namespace Tests\Unit\Infra\Route\Enum;

use App\Infra\Route\Enum\RouteNameEnum;
use Tests\UnitTestCase;

class RouteNameEnumUnitTest extends UnitTestCase
{
    public function testEnum()
    {
        $this->assertEquals('api.auth.login', RouteNameEnum::ApiAuthLogin->value);

        $this->assertEquals('api.product.list', RouteNameEnum::ApiProductList->value);

        $this->assertEquals('api.payment.method.list', RouteNameEnum::ApiPaymentMethodList->value);

        $this->assertEquals('api.cart.item.create', RouteNameEnum::ApiCartItemCreate->value);
        $this->assertEquals('api.cart.item.update', RouteNameEnum::ApiCartItemUpdate->value);
        $this->assertEquals('api.cart.item.delete', RouteNameEnum::ApiCartItemDelete->value);

        $this->assertEquals('api.cart.update', RouteNameEnum::ApiCartUpdate->value);
        $this->assertEquals('api.cart.delete', RouteNameEnum::ApiCartDelete->value);
    }
}
