<?php

namespace App\Infra\Error;

use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorReport
{
    /** @codeCoverageIgnore */
    public static function report(Throwable $exception): void
    {
        // Aqui é onde pode ser implementado o código para monitoramento de erros, como, por exemplo, o Sentry
        Log::error($exception->getMessage());
    }
}
