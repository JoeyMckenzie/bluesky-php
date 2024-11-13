<?php

declare(strict_types=1);

namespace Tests\ValueObjects;

use Bluesky\Enums\HttpMethod;
use Bluesky\Enums\MediaType;
use Bluesky\ValueObjects\Connector\BaseUri;
use Bluesky\ValueObjects\Connector\Headers;
use Bluesky\ValueObjects\Connector\QueryParams;
use Bluesky\ValueObjects\Payload;
use Bluesky\ValueObjects\ResourceUri;

covers(Payload::class);

describe(Payload::class, function (): void {
    beforeEach(function (): void {
        $this->baseUri = BaseUri::from('example.com');
        $this->headers = Headers::create();
        $this->queryParams = QueryParams::create();
    });

    describe('constructor defaults', function (): void {
        it('defaults includeBody to true', function (): void {
            // Arrange & Act - create with minimum required params
            $payload = new Payload(
                MediaType::JSON,
                HttpMethod::POST,
                ResourceUri::create('test.resource'),
                ['test' => 'data']
            );

            // Assert property
            expect($payload)->toHaveProperty('includeBody', true);

            // Verify behavior
            $request = $payload->toRequest(
                BaseUri::from('example.com'),
                Headers::create(),
                QueryParams::create()
            );

            $body = $request->getBody();
            expect($body->getContents())->not->toBe('');
            $body->rewind();
            expect(json_decode($body->getContents(), true))->toBe(['test' => 'data']);
        });
    });

    describe('GET requests', function (): void {
        it('creates basic GET requests', function (): void {
            // Arrange & Act
            $payload = Payload::get('test.resource');
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->getMethod())->toBe('GET')
                ->and($request->getUri()->getPath())->toBe('/test.resource')
                ->and($request->hasHeader('Accept'))->toBeTrue()
                ->and($request->getHeaderLine('Accept'))->toBe('application/json')
                ->and($request->getBody()->getContents())->toBe('');
        });

        it('handles query parameters in GET requests', function (): void {
            // Arrange & Act
            $params = ['key1' => 'value1', 'key2' => 'value2'];
            $payload = Payload::get('test.resource', $params);
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            parse_str($request->getUri()->getQuery(), $result);
            expect($result)
                ->toHaveKey('key1')
                ->toHaveKey('key2')
                ->and($result['key1'])->toBe('value1')
                ->and($result['key2'])->toBe('value2');
        });

        it('creates retrieve requests with ID', function (): void {
            // Arrange & Act
            $payload = Payload::retrieve('test.resource', 'test-id');
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->getMethod())->toBe('GET')
                ->and($request->getUri()->getPath())->toBe('/test.resource/test-id');
        });

        it('merges query parameters from multiple sources', function (): void {
            // Arrange
            $payloadParams = ['key1' => 'value1'];
            $extraQueryParams = QueryParams::create()->withParam('key2', 'value2');

            // Act
            $payload = Payload::get('test.resource', $payloadParams);
            $request = $payload->toRequest($this->baseUri, $this->headers, $extraQueryParams);

            // Assert
            parse_str($request->getUri()->getQuery(), $result);
            expect($result)
                ->toHaveKey('key1')
                ->toHaveKey('key2')
                ->and($result['key1'])->toBe('value1')
                ->and($result['key2'])->toBe('value2');
        });
    });

    describe('POST requests', function (): void {
        it('creates basic POST requests', function (): void {
            // Arrange
            $params = ['test' => 'data'];

            // Act
            $payload = Payload::post('test.resource', $params);
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->getMethod())->toBe(HttpMethod::POST->value)
                ->and($request->getUri()->getPath())->toBe('/test.resource')
                ->and($request->hasHeader('Accept'))->toBeTrue()
                ->and($request->getHeaderLine('Accept'))->toBe('application/json');

            // Verify body
            $body = $request->getBody()->getContents();
            expect($body)->toBeJson()
                ->and(json_decode($body, true))->toBe($params);
        });

        it('handles custom content type', function (): void {
            // Arrange
            $params = ['test' => 'data'];
            $contentType = MediaType::MULTIPART;

            // Act
            $payload = Payload::post('test.resource', $params, $contentType);
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->hasHeader('Content-Type'))->toBeTrue()
                ->and($request->getHeaderLine('Content-Type'))->toBe(MediaType::MULTIPART->value);
        });

        it('handles custom headers', function (): void {
            // Arrange
            $params = ['test' => 'data'];
            $customHeaders = ['X-Custom' => 'test-value'];

            // Act
            $payload = Payload::post('test.resource', $params, null, $customHeaders);
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->hasHeader('X-Custom'))->toBeTrue()
                ->and($request->getHeaderLine('X-Custom'))->toBe('test-value');
        });

        it('respects includeBody flag', function (): void {
            // Arrange
            $params = ['test' => 'data'];

            // Act - with body
            $payloadWithBody = Payload::post('test.resource', $params, null, [], true);
            $requestWithBody = $payloadWithBody->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Act - without body
            $payloadWithoutBody = Payload::post('test.resource', $params, null, [], false);
            $requestWithoutBody = $payloadWithoutBody->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($requestWithBody->getBody()->getContents())->not->toBe('')
                ->and($requestWithoutBody->getBody()->getContents())->toBe('');
        });

        it('includes body by default in standard POST requests', function (): void {
            // Arrange
            $params = ['test' => 'data'];

            // Act
            $payload = Payload::post('test.resource', $params);
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            $body = $request->getBody()->getContents();
            expect($body)->not->toBe('')
                ->and(json_decode($body, true))->toBe($params);
        });

        it('includes body by default in postWithoutResponse', function (): void {
            // Arrange
            $params = ['test' => 'data'];

            // Act
            $payload = Payload::postWithoutResponse('test.resource', $params);
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            $body = $request->getBody()->getContents();
            expect($body)->not->toBe('')
                ->and(json_decode($body, true))->toBe($params);
        });

        it('defaults to not skipping response in standard post', function (): void {
            // Arrange
            $params = ['test' => 'data'];

            // Act
            $payload = Payload::post('test.resource', $params);

            // Assert
            expect($payload)->toHaveProperty('skipResponse', false);
        });

        it('always skips response in postWithoutResponse', function (): void {
            // Arrange
            $params = ['test' => 'data'];

            // Act
            $payload = Payload::postWithoutResponse('test.resource', $params);

            // Assert
            expect($payload)->toHaveProperty('skipResponse', true);
        });

        it('defaults includeBody to true when not specified', function (): void {
            // Arrange
            $params = ['test' => 'data'];

            // Test standard POST - multiple variants
            // 1. Default construction via static method
            $standardPayload = Payload::post('test.resource', $params);
            $standardRequest = $standardPayload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // 2. Without explicitly specifying includeBody
            $implicitPayload = Payload::post('test.resource', $params);
            $implicitRequest = $implicitPayload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // 3. With explicit null headers
            $nullHeadersPayload = Payload::post('test.resource', $params, null, null);
            $nullHeadersRequest = $nullHeadersPayload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert all variants defaulted to true
            expect($standardPayload)->toHaveProperty('includeBody', true)
                ->and($implicitPayload)->toHaveProperty('includeBody', true)
                ->and($nullHeadersPayload)->toHaveProperty('includeBody', true);

            // Verify bodies are included in all variants
            $standardBody = $standardRequest->getBody();
            $implicitBody = $implicitRequest->getBody();
            $nullHeadersBody = $nullHeadersRequest->getBody();

            // Test standard request
            expect($standardBody->getContents())->not->toBe('');
            $standardBody->rewind();
            expect(json_decode($standardBody->getContents(), true))->toBe($params);

            // Test implicit request
            expect($implicitBody->getContents())->not->toBe('');
            $implicitBody->rewind();
            expect(json_decode($implicitBody->getContents(), true))->toBe($params);

            // Test null headers request
            expect($nullHeadersBody->getContents())->not->toBe('');
            $nullHeadersBody->rewind();
            expect(json_decode($nullHeadersBody->getContents(), true))->toBe($params);

            // Verify that explicit false overrides default
            $noBodyPayload = Payload::post('test.resource', $params, null, [], false);
            $noBodyRequest = $noBodyPayload->toRequest($this->baseUri, $this->headers, $this->queryParams);
            expect($noBodyPayload)->toHaveProperty('includeBody', false)
                ->and($noBodyRequest->getBody()->getContents())->toBe('');

            // Test that every constructor permutation with default includes body
            $contentTypes = [null, MediaType::JSON, MediaType::MULTIPART];
            $headerVariants = [null, [], ['X-Test' => 'value']];

            foreach ($contentTypes as $contentType) {
                foreach ($headerVariants as $headers) {
                    $payload = Payload::post('test.resource', $params, $contentType, $headers);
                    $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

                    expect($payload)
                        ->toHaveProperty('includeBody', true);

                    $body = $request->getBody();
                    expect($body->getContents())->not->toBe('');
                    $body->rewind();
                    expect(json_decode($body->getContents(), true))->toBe($params);
                }
            }
        });
    });

    describe('header handling', function (): void {
        it('combines headers from multiple sources', function (): void {
            // Arrange
            $baseHeaders = Headers::create()->withCustomHeader('X-Base', 'base-value');
            $payloadHeaders = ['X-Custom' => 'custom-value'];

            // Act
            $payload = Payload::post('test.resource', [], null, $payloadHeaders);
            $request = $payload->toRequest($this->baseUri, $baseHeaders, $this->queryParams);

            // Assert
            expect($request->hasHeader('X-Base'))->toBeTrue()
                ->and($request->getHeaderLine('X-Base'))->toBe('base-value')
                ->and($request->hasHeader('X-Custom'))->toBeTrue()
                ->and($request->getHeaderLine('X-Custom'))->toBe('custom-value');
        });

        it('properly sets Accept header', function (): void {
            // Arrange & Act
            $payload = Payload::get('test.resource');
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->hasHeader('Accept'))->toBeTrue()
                ->and($request->getHeaderLine('Accept'))->toBe('application/json');
        });

        it('handles null headers array correctly', function (): void {
            // Arrange
            $payload = Payload::post('test.resource', ['data' => 'test'], MediaType::JSON, null);

            // Act
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->getHeaders())
                ->toHaveKey('Accept')
                ->toHaveKey('Content-Type')
                ->and($request->getHeaderLine('Accept'))->toBe('application/json')
                ->and($request->getHeaderLine('Content-Type'))->toBe('application/json');
        });

        it('handles empty headers array correctly', function (): void {
            // Arrange
            $payload = Payload::post('test.resource', ['data' => 'test'], MediaType::JSON);

            // Act
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->getHeaders())
                ->toHaveKey('Accept')
                ->toHaveKey('Content-Type')
                ->and($request->getHeaderLine('Accept'))->toBe('application/json')
                ->and($request->getHeaderLine('Content-Type'))->toBe('application/json');
        });

        it('requires both null check and empty array check for headers', function (): void {
            // This test ensures we need both conditions in the if statement

            // Test with null headers
            $payloadNull = Payload::post('test.resource', ['data' => 'test'], MediaType::JSON, null);
            $requestNull = $payloadNull->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Test with empty array headers
            $payloadEmpty = Payload::post('test.resource', ['data' => 'test'], MediaType::JSON, []);
            $requestEmpty = $payloadEmpty->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Test with custom headers
            $customHeaders = ['X-Custom' => 'value'];
            $payloadCustom = Payload::post('test.resource', ['data' => 'test'], MediaType::JSON, $customHeaders);
            $requestCustom = $payloadCustom->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            // Headers should be processed differently for each case
            expect($requestNull->getHeaders())
                ->toHaveKey('Content-Type')
                ->not->toHaveKey('X-Custom')
                ->and($requestEmpty->getHeaders())
                ->toHaveKey('Content-Type')
                ->not->toHaveKey('X-Custom')
                ->and($requestCustom->getHeaders())
                ->toHaveKey('Content-Type')
                ->toHaveKey('X-Custom');

            // Verify the conditions are independent
            $baseHeaders = array_diff_key($requestNull->getHeaders(), ['X-Custom' => '']);
            $customHeaders = array_diff_key($requestCustom->getHeaders(), ['X-Custom' => '']);
            expect($baseHeaders)->toBe($requestEmpty->getHeaders())
                ->and($baseHeaders)->toBe($customHeaders);
        });

        it('sets Content-Type header when explicitly provided', function (): void {
            // Arrange
            $payload = Payload::post('test.resource', ['data' => 'test'], MediaType::JSON);

            // Act
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->getHeaders())
                ->toHaveKey('Content-Type')
                ->and($request->getHeaderLine('Content-Type'))->toBe('application/json');
        });

        it('omits Content-Type header when not needed', function (): void {
            // Arrange
            $payload = Payload::get('test.resource');

            // Act
            $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

            // Assert
            expect($request->getHeaders())
                ->not->toHaveKey('Content-Type');
        });
    });
});
