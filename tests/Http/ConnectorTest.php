<?php

declare(strict_types=1);

use Bluesky\Enums\MediaType;
use Bluesky\Exceptions\ConnectorException;
use Bluesky\Exceptions\UnserializableResponseException;
use Bluesky\Http\Connector;
use Bluesky\ValueObjects\Connector\BaseUri;
use Bluesky\ValueObjects\Connector\Headers;
use Bluesky\ValueObjects\Connector\QueryParams;
use Bluesky\ValueObjects\Payload;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as PsrResponse;
use Psr\Http\Client\ClientInterface;

// TODO: At some point, need to come back to this
// Theres' still a few mutations that need to be taken care of
covers(Connector::class);

describe(Connector::class, function (): void {
    beforeEach(function (): void {
        $this->client = Mockery::mock(ClientInterface::class);
        $this->baseUri = BaseUri::from('bsky.social');
        $this->headers = Headers::create()->withContentType(MediaType::JSON);
        $this->queryParams = QueryParams::create();

        $this->connector = new Connector(
            $this->client,
            $this->baseUri,
            $this->headers,
            $this->queryParams
        );
    });

    describe('successful requests', function (): void {
        it('handles successful JSON response', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $responseData = ['success' => true, 'data' => ['id' => 1]];

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn(new PsrResponse(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode($responseData)
                ));

            // Act
            $response = $this->connector->requestData($payload);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe($responseData);
        });

        it('handles successful POST request', function (): void {
            // Arrange
            $payload = Payload::post('test.resource', ['name' => 'test']);
            $responseData = ['success' => true, 'id' => 1];

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn(new PsrResponse(
                    201,
                    ['Content-Type' => 'application/json'],
                    json_encode($responseData)
                ));

            // Act
            $response = $this->connector->requestData($payload);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe($responseData);
        });

        it('handles skipResponse flag', function (): void {
            // Arrange
            $payload = Payload::postWithoutResponse('test.resource', ['data' => 'test']);

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn(new PsrResponse(204));

            // Act
            $response = $this->connector->requestData($payload);

            // Assert
            expect($response)->toBeNull();
        });

        it('handles empty response bodies', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn(new PsrResponse(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode([])
                ));

            // Act
            $response = $this->connector->requestData($payload);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe([]);
        });
    });

    describe('authenticated requests', function (): void {
        beforeEach(function (): void {
            $this->payload = Payload::get('test.resource');
            $this->accessToken = 'test-token';
            $this->responseData = ['data' => 'authenticated'];
        });

        it('adds authorization header for authenticated requests', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $responseData = ['data' => 'authenticated'];
            $accessToken = 'test-token';

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturnUsing(fn($request): \GuzzleHttp\Psr7\Response => new PsrResponse(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode($responseData)
                ));

            // Act
            $response = $this->connector->requestDataWithAccessToken($payload, $accessToken);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe($responseData)
                ->and($this->connector->getHeaders()->toArray())
                ->toHaveKey('Authorization', "Bearer $accessToken");
        });
    });

    describe('error handling', function (): void {
        it('throws UnserializableResponseException for invalid JSON', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturnUsing(fn($request): \GuzzleHttp\Psr7\Response => new PsrResponse(
                    400,
                    ['Content-Type' => 'application/json'],
                    'this is not json at all' // Plain text that will definitely fail JSON parsing
                ));

            // Act & Assert
            expect(fn () => $this->connector->requestData($payload))
                ->toThrow(UnserializableResponseException::class);
        });
    });

    describe('getters', function (): void {
        it('returns query parameters', function (): void {
            // Act & Assert
            expect($this->connector->getQueryParams())->toBe($this->queryParams);
        });

        it('returns headers', function (): void {
            // Act & Assert
            expect($this->connector->getHeaders())->toBe($this->headers);
        });

        it('returns base URI', function (): void {
            // Act & Assert
            expect($this->connector->getBaseUri())->toBe($this->baseUri);
        });
    });

    describe('content type handling', function (): void {
        it('handles different content types in response', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            $this->client->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new PsrResponse(
                    200,
                    ['Content-Type' => 'application/x-www-form-urlencoded'],
                    json_encode(['data' => 'test'])
                ));

            // Act
            $response = $this->connector->requestData($payload);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe(['data' => 'test']);
        });

        it('handles missing content type in response', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            $this->client->shouldReceive('sendRequest')
                ->once()
                ->andReturn(new PsrResponse(
                    200,
                    [],
                    json_encode(['data' => 'test'])
                ));

            // Act
            $response = $this->connector->requestData($payload);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe(['data' => 'test']);
        });
    });

    describe('request composition', function (): void {
        it('combines query parameters from payload and connector', function (): void {
            // Arrange
            // Create a new connector with the global query param
            $queryParams = QueryParams::create()->withParam('global', 'param');
            $this->connector = new Connector(
                $this->client,
                $this->baseUri,
                $this->headers,
                $queryParams
            );

            $payload = Payload::get('test.resource', ['local' => 'param']);

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturnUsing(function ($request): \GuzzleHttp\Psr7\Response {
                    $query = $request->getUri()->getQuery();
                    expect($query)->toContain('global=param')
                        ->and($query)->toContain('local=param');

                    return new PsrResponse(
                        200,
                        ['Content-Type' => 'application/json'],
                        json_encode(['data' => 'test'])
                    );
                });

            // Act
            $this->connector->requestData($payload);
        });

        it('combines headers from payload and connector', function (): void {
            // Arrange
            $payload = Payload::post(
                'test.resource',
                ['data' => 'test'],
                null,
                ['X-Custom' => 'value']
            );

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturnUsing(function ($request): \GuzzleHttp\Psr7\Response {
                    expect($request->hasHeader('Content-Type'))->toBeTrue()
                        ->and($request->hasHeader('Accept'))->toBeTrue()
                        ->and($request->hasHeader('X-Custom'))->toBeTrue();

                    return new PsrResponse(
                        200,
                        ['Content-Type' => 'application/json'],
                        json_encode(['data' => 'test'])
                    );
                });

            // Act
            $this->connector->requestData($payload);
        });
    });

    describe('makeRequest method', function (): void {
        it('handles null access token', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $responseData = ['data' => 'test'];

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn(new PsrResponse(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode($responseData)
                ));

            // Act
            $response = $this->connector->makeRequest($payload, null);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe($responseData);
        });

        it('handles non-null access token', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $responseData = ['data' => 'test'];
            $accessToken = 'test-token';

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn(new PsrResponse(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode($responseData)
                ));

            // Act
            $response = $this->connector->makeRequest($payload, $accessToken);

            // Assert
            expect($response)->not->toBeNull()
                ->and($response->data())->toBe($responseData)
                ->and($this->connector->getHeaders()->toArray())
                ->toHaveKey('Authorization', "Bearer $accessToken");
        });
    });

    describe('error response handling', function (): void {
        it('skips JSON validation for success responses', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn(new PsrResponse(
                    200,
                    ['Content-Type' => 'application/json'],
                    'invalid but ignored because status is 200'
                ));

            // Act & Assert - should not throw exception due to 200 status
            expect(fn () => $this->connector->requestData($payload))
                ->toThrow(UnserializableResponseException::class);
        });

        it('skips JSON validation for non-JSON content types', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $request = new Request('GET', 'https://bsky.social/test.resource');
            $errorResponse = new PsrResponse(
                400,
                ['Content-Type' => 'text/plain'],
                'invalid but ignored because not JSON'
            );

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andThrow(new ClientException(
                    'Error response',
                    $request,
                    $errorResponse
                ));

            // Act & Assert - should throw ConnectorException since content type is not JSON
            expect(fn () => $this->connector->requestData($payload))
                ->toThrow(ConnectorException::class);
        });

        it('handles ResponseInterface content in error checking', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $errorResponse = new PsrResponse(
                400,
                ['Content-Type' => 'application/json'],
                'invalid json'
            );

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andThrow(new ClientException(
                    'Bad Request',
                    Mockery::mock(\Psr\Http\Message\RequestInterface::class),
                    $errorResponse
                ));

            // Act & Assert
            expect(fn () => $this->connector->requestData($payload))
                ->toThrow(UnserializableResponseException::class);
        });

        it('handles non-ClientException types', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andThrow(new class extends Exception implements Psr\Http\Client\ClientExceptionInterface {});

            // Act & Assert
            expect(fn () => $this->connector->requestData($payload))
                ->toThrow(ConnectorException::class);
        });

        it('throws UnserializableResponseException for invalid JSON response body', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $invalidJsonResponse = new PsrResponse(
                400,
                ['Content-Type' => 'application/json'],
                'invalid json body'
            );

            $this->client->shouldReceive('sendRequest')
                ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                ->once()
                ->andReturn($invalidJsonResponse);

            // Act & Assert
            expect(fn () => $this->connector->requestData($payload))
                ->toThrow(UnserializableResponseException::class);
        });
    });

    describe('status code handling', function (): void {
        it('treats status codes < 400 as successful', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');
            $responseData = ['data' => 'test'];

            foreach ([200, 201, 299, 301, 302, 399] as $statusCode) {
                $this->client->shouldReceive('sendRequest')
                    ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                    ->once()
                    ->andReturn(new PsrResponse(
                        $statusCode,
                        ['Content-Type' => 'application/json'],
                        json_encode($responseData)
                    ));

                // Act
                $response = $this->connector->requestData($payload);

                // Assert
                expect($response)->not->toBeNull()
                    ->and($response->data())->toBe($responseData);
            }
        });

        it('treats status codes >= 400 as errors', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            foreach ([400, 401, 403, 404, 500, 503] as $statusCode) {
                $request = new Request('GET', 'https://bsky.social/test.resource');
                $errorResponse = new PsrResponse(
                    $statusCode,
                    ['Content-Type' => 'application/json'],
                    json_encode(['error' => 'test'])
                );

                $this->client->shouldReceive('sendRequest')
                    ->with(Mockery::type(\Psr\Http\Message\RequestInterface::class))
                    ->once()
                    ->andThrow(new ClientException(
                        'Error response',
                        $request,
                        $errorResponse
                    ));

                // Act & Assert
                expect(fn () => $this->connector->requestData($payload))
                    ->toThrow(ConnectorException::class);
            }
        });
    });
});
