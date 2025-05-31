<?php

namespace App\Infra\Route\Enum;

enum RouteNameEnum: string
{
    case ApiAuthLogin = 'api.auth.login';

    case ApiProductList = 'api.product.list';

    case ApiPaymentMethodList = 'api.payment.method.list';

    case ApiCartItemCreate = 'api.cart.item.create';
    case ApiCartItemUpdate = 'api.cart.item.update';
    case ApiCartItemDelete = 'api.cart.item.delete';

    case ApiCartUpdate = 'api.cart.update';
    case ApiCartDelete = 'api.cart.delete';
}
