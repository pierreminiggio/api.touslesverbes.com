<?php

namespace App;

use App\Controller\DocController;
use App\Controller\ErrorController;
use App\Controller\Verb\AllVerbsController;
use App\Controller\Verb\FindVerbController;
use App\Crypt\CrypterFactory;
use App\Database\DatabaseFetcherFactory;
use App\Http\MethodEnum;
use App\Http\Response\RedirectionResponse;
use App\Http\Response\Response;
use App\Repository\CryptedVerbRepository;

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
            $httpMethod === MethodEnum::GET && $path === '/' => new RedirectionResponse($host . '/doc'),
            $httpMethod === MethodEnum::GET && $path === '/doc' => (new DocController($host))(),
            $httpMethod === MethodEnum::GET && $path === '/verbs' => (new AllVerbsController(
                new CryptedVerbRepository(CrypterFactory::make(), DatabaseFetcherFactory::make())
            ))(),
            $httpMethod === MethodEnum::GET && strpos($path, '/verbs/exact-search') === 0 => 'WIP',
            $httpMethod === MethodEnum::GET && strpos($path, '/verbs/search') === 0 => 'WIP',
            $httpMethod === MethodEnum::GET && strpos($path, '/verbs/') === 0 => (new FindVerbController(
                new CryptedVerbRepository(CrypterFactory::make(), DatabaseFetcherFactory::make())
            ))(substr($path, 7)),
            default => (new ErrorController())->error404(),
        };

        $response->execute();
    }
}
