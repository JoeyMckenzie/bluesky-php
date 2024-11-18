<?php

declare(strict_types=1);

namespace Tests;

use Bluesky\Bluesky;
use Bluesky\Builder;
use Bluesky\Client;
use Bluesky\Enums\TargetApi;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\ATProto\session;

covers(Bluesky::class);

describe(Bluesky::class, function (): void {
    beforeEach(function (): void {
        ClientMock::reset();
    });

    it('can create client with session', function (): void {
        // Arrange
        $username = 'username';
        $password = 'password123';
        $sessionData = session();

        // Set up the mock FIRST
        $mockClient = ClientMock::createForPost(
            'com.atproto.server.createSession',
            [
                'identifier' => $username,
                'password' => $password,
            ],
            Response::from($sessionData),
            'requestData'
        );

        // Act - use clientWithSession which will use our mock
        $client = Bluesky::clientWithSession($username, $password, $mockClient);

        // Assert
        expect($client)
            ->toBeInstanceOf(Client::class)
            ->and($client->username)->toBe($username)
            ->and($client->getAccessJwt())->toBe($sessionData['accessJwt'])
            ->and($client->getRefreshJwt())->toBe($sessionData['refreshJwt']);
    });

    it('creates default client', function (): void {
        // Act
        $client = Bluesky::client('testuser');

        // Assert
        expect($client)
            ->toBeInstanceOf(Client::class)
            ->and($client->username)->toBe('testuser')
            ->and($client->getAccessJwt())->toBeNull()
            ->and($client->getRefreshJwt())->toBeNull();

        // Verify default API endpoint
        $baseUri = $client->connector->getBaseUri();
        expect((string) $baseUri)->toBe('https://bsky.social/xrpc/');

        // Verify User-Agent header
        $headers = $client->connector->getHeaders()->toArray();
        expect($headers)
            ->toHaveKey('User-Agent')
            ->and($headers['User-Agent'])->toMatch('/^bluesky-php-client\/\d+\.\d+\.\d+$/');
    });

    it('creates builder instance', function (): void {
        // Act
        $builder = Bluesky::builder();

        // Assert
        expect($builder)
            ->toBeInstanceOf(Builder::class)
            // Verify builder starts with default values
            ->and($builder->getUsername())->toBeNull()
            ->and($builder->getHeaders())->toBe([])
            ->and($builder->getQueryParams())->toBe([])
            ->and($builder->getBaseUri())->toBe(Client::API_BASE_URL);
    });

    it('creates public client', function (): void {
        // Act
        $client = Bluesky::publicClient();

        // Assert
        expect($client)
            ->toBeInstanceOf(Client::class)
            ->and($client->username)->toBe('')
            ->and($client->getAccessJwt())->toBeNull()
            ->and($client->getRefreshJwt())->toBeNull();

        // Verify public API endpoint
        $baseUri = $client->connector->getBaseUri();
        expect((string) $baseUri)->toBe('https://public.api.bsky.app/xrpc/');

        // Verify User-Agent header
        $headers = $client->connector->getHeaders()->toArray();
        expect($headers)
            ->toHaveKey('User-Agent')
            ->and($headers['User-Agent'])->toMatch('/^bluesky-php-client\/\d+\.\d+\.\d+$/');
    });

    it('maintains builder customizations', function (): void {
        // Act
        $client = Bluesky::builder()
            ->withUsername('testuser')
            ->withHeader('X-Custom', 'value')
            ->withQueryParam('test', 'value')
            ->withTargetApi(TargetApi::PUBLIC)
            ->withHeader('User-Agent', 'bluesky-php-client/1.0.0')
            ->build();

        // Assert
        expect($client->username)->toBe('testuser');

        // Verify headers
        $headers = $client->connector->getHeaders()->toArray();
        expect($headers)
            ->toHaveKey('X-Custom')
            ->toHaveKey('User-Agent')
            ->and($headers['X-Custom'])->toBe('value');

        // Verify query params
        $params = $client->connector->getQueryParams()->toArray();
        expect($params)->toHaveKey('test')
            ->and($params['test'])->toBe('value');

        // Verify API endpoint
        $baseUri = $client->connector->getBaseUri();
        expect((string) $baseUri)->toBe('https://public.api.bsky.app/xrpc/');
    });

    it('creates authenticated client by default', function (): void {
        // Act
        $client = Bluesky::client('testuser');

        // Assert base URI indicates authenticated API
        $baseUri = $client->connector->getBaseUri();
        expect((string) $baseUri)->toBe('https://bsky.social/xrpc/');
    });
});
