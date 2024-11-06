<?php

declare(strict_types=1);

namespace Tests\Mocks;

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

use function Tests\Fixtures\stubJwt;

final class ClientMock
{
    public static function mockClient(HttpMethod $method, string $resource, array $params, Response|ResponseInterface|string $response, string $methodName = 'requestDataWithAccessToken', bool $validateParams = true, array $additionalHeaders = []): Client
    {
        $connector = Mockery::mock(ConnectorContract::class);
        $connector
            ->shouldReceive($methodName)
            ->once()
            ->withArgs(function (Payload $payload) use ($method, $resource, $params, $validateParams, $additionalHeaders): bool {
                $headers = Headers::create()->withAccessToken('token');

                foreach ($additionalHeaders as $name => $value) {
                    $headers = $headers->withCustomHeader($name, $value);
                }

                $baseUri = BaseUri::from('bsky.social/xrpc');
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
                    }

                    if ($method === HttpMethod::POST) {
                        if ($payload->includeBody) {
                            if ($request->getBody()->getContents() !== json_encode($params)) {
                                return false;
                            }
                        } else {
                            $size = $request->getBody()->getSize();
                            if ($size !== null && $size > 0) {
                                return false;
                            }
                        }
                    }
                }

                return $request->getMethod() === $method->value && $path === "/xrpc/$resource";
            })->andReturn($response);

        return new Client($connector, 'username', stubJwt());
    }
}
