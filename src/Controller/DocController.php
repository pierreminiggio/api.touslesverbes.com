<?php

namespace App\Controller;

use App\Http\Response\HttpOkResponse;
use App\Http\Response\Response;

class DocController
{

    public function __construct(private string $host)
    {}

    public function __invoke(): Response
    {
        $view = <<<HTML
            <html>
            <head>
                <meta charset="utf-8">
                <title>API Documentation</title>
                <link rel="stylesheet" href="{{ URL::to('/openapi/swagger-ui.css') }}">
            </head>

            <body>
                <div id="swagger-ui"></div>

                <script src="https://unpkg.com/swagger-ui-dist@3/swagger-ui-bundle.js"></script>
                <script>
                window.onload = function() {
                    window.ui = SwaggerUIBundle({url: "{$this->host}/openapi/doc.json", dom_id: '#swagger-ui'})
                }
                </script>
            </body>
        </html>
        HTML;
        
        return new HttpOkResponse($view);
    }
}