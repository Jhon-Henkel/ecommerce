<?php

namespace App\Infra\Controller\Read;

use App\Infra\Controller\Controller;
use App\Infra\Response\Api\ResponseApi;
use App\Infra\UseCase\Read\IGetUseCase;
use Illuminate\Http\JsonResponse;

abstract class BaseGetController extends Controller
{
    abstract protected function getUseCase(): IGetUseCase;

    public function __invoke(int $id): JsonResponse
    {
        $result = $this->getUseCase()->execute($id);
        return ResponseApi::renderOk($result);
    }
}
