<?php

use App\Infra\Error\ErrorReport;
use App\Infra\Response\Api\ResponseApi;
use App\Infra\Response\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ModelNotFoundException|NotFoundHttpException $e) {
            return ResponseApi::renderNotFount();
        });

        $exceptions->renderable(function (BadRequestException $e) {
            return ResponseApi::renderBadRequest($e->getMessage());
        });

        $exceptions->renderable(function (Throwable $e) {
            ErrorReport::report($e);
            return ResponseApi::renderInternalServerError($e->getMessage());
        });
    })->create();
