<?php

namespace App\Infra\Controller\Create;

use App\Infra\Controller\Controller;
use App\Infra\Request\Validation\Validator;
use App\Infra\Response\Api\ResponseApi;
use App\Infra\UseCase\Create\ICreateUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseCreateController extends Controller
{
    abstract protected function getUseCase(): ICreateUseCase;
    abstract protected function getRules(): array;

    public function __invoke(Request $request): JsonResponse
    {
        Validator::validateRequest($request, $this->getRules());
        $requestData = $request->json()->all();
        $result = $this->getUseCase()->execute($requestData);
        return ResponseApi::renderCreated($result);
    }
}
