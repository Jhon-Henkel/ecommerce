<?php

namespace App\Infra\Route\Enum;

enum RouteNameEnum: string
{
    case ApiAuthLogin = 'api.auth.login';

    case ApiProductList = 'api.product.list';

    case ApiPaymentMethodList = 'api.payment.method.list';
}
