<?php

use App\Router;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
$host = $protocol . '://' . $_SERVER['HTTP_HOST'];

if (substr($host, -1) == '/') {
    $host = substr($host, 0, -1);
}

$requestUrl = $_SERVER['REQUEST_URI'];
$queryParameters = ! empty($_SERVER['QUERY_STRING']) ? ('?' . $_SERVER['QUERY_STRING']) : null;
$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
$requestBody = file_get_contents('php://input') ?? null;

(new Router())->run(
    $host,
    $queryParameters
        ? str_replace($queryParameters, '', $requestUrl)
        : $requestUrl
    ,
    $httpMethod,
    $queryParameters,
    $authHeader,
    $requestBody
);
