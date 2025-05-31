<?php

namespace App\Infra\Controller\Update;

use App\Infra\Controller\Controller;
use App\Infra\Request\Validation\Validator;
use App\Infra\Response\Api\ResponseApi;
use App\Infra\UseCase\Update\IUpdateUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseUpdateController extends Controller
{
    abstract protected function getUseCase(): IUpdateUseCase;
    abstract protected function getRules(): array;

    public function __invoke(Request $request, int $id): JsonResponse
    {
        Validator::validateRequest($request, $this->getRules());
        $requestData = $request->json()->all();
        $result = $this->getUseCase()->execute($requestData, $id);
        return ResponseApi::renderOk($result);
    }
}
