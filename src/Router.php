<?php

namespace App;

use App\Controller\DocController;
use App\Controller\ErrorController;
use App\Http\MethodEnum;
use App\Http\Response\Response;

class Router
{

    public function run(
        string $host,
        string $path,
        string $httpMethod,
        ?string $queryParameters,
        ?string $authHeader,
        ?string $requestBody
    ): void
    {

        /** @var Response $response */
        $response = match (true) {
            $httpMethod === MethodEnum::GET && $path === '/doc' => (new DocController($host))(),
            default => (new ErrorController())->error404(),
        };

        $response->render();
    }
}
