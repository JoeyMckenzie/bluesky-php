<?php

declare(strict_types=1);

namespace Tests;

use Bluesky\Client;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Enums\HttpMethod;
use Bluesky\ValueObjects\Connector\BaseUri;
use Bluesky\ValueObjects\Connector\Headers;
use Bluesky\ValueObjects\Connector\QueryParams;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Mockery;
use Psr\Http\Message\ResponseInterface;

foreach (glob(__DIR__.'/Fixtures/*Fixture.php') as $fixture) {
    require_once $fixture;
}

function mockClient(HttpMethod $method, string $resource, array $params, Response|ResponseInterface|string $response, string $methodName = 'requestData', bool $validateParams = true): Client
{
    $connector = Mockery::mock(ConnectorContract::class);
    $connector
        ->shouldReceive($methodName)
        ->once()
        ->withArgs(function (Payload $payload) use ($method, $resource, $params, $validateParams): bool {
            $baseUri = BaseUri::from('bsky.social/xrpc');
            $headers = Headers::create()->withAccessToken('token');
            $queryParams = QueryParams::create();
            $request = $payload->toRequest($baseUri, $headers, $queryParams);
            $path = $request->getUri()->getPath();

            if ($validateParams) {
                if ($method === HttpMethod::GET) {
                    $query = $request->getUri()->getQuery();
                    $expectedQuery = http_build_query($params);
                    if ($query !== $expectedQuery) {
                        return false;
                    }
                } elseif ($request->getBody()->getContents() !== json_encode($params)) {
                    return false;
                }
            }

            return $request->getMethod() === $method->value && $path === "/xrpc/$resource";
        })->andReturn($response);

    return new Client($connector, 'username');
}
