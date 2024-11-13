<?php

declare(strict_types=1);

namespace Tests;

use Bluesky\Client;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Exceptions\AuthenticationException;
use Bluesky\Resources\Actor;
use Bluesky\Resources\Feed;
use Bluesky\Resources\Session;
use Bluesky\ValueObjects\Connector\Response;
use Mockery;
use ReflectionObject;

use function Tests\Fixtures\refreshedSession;
use function Tests\Fixtures\session;

covers(Client::class);

describe('Client', function (): void {
    beforeEach(function (): void {
        $this->username = 'testuser';
        $this->connector = Mockery::mock(ConnectorContract::class);
        $this->client = new Client($this->connector, $this->username);
    });

    it('creates resource instances', function (): void {
        expect($this->client->session())->toBeInstanceOf(Session::class)
            ->and($this->client->actor())->toBeInstanceOf(Actor::class)
            ->and($this->client->feed())->toBeInstanceOf(Feed::class);
    });

    it('creates new session', function (): void {
        // Arrange
        $password = 'password123';
        $sessionData = session();

        $this->connector->shouldReceive('requestData')
            ->once()
            ->andReturn(Response::from($sessionData));

        // Act
        $result = $this->client->newSession($password);

        // Assert
        expect($result)->toBe($this->client)
            ->and($this->client->getAccessJwt())->toBe($sessionData['accessJwt'])
            ->and($this->client->getRefreshJwt())->toBe($sessionData['refreshJwt']);
    });

    it('refreshes session with valid refresh token', function (): void {
        // Arrange
        $initialRefreshToken = 'initial-refresh-token';
        $this->client = new Client($this->connector, $this->username, null, $initialRefreshToken);

        $refreshedData = refreshedSession();
        $this->connector->shouldReceive('requestData')
            ->once()
            ->andReturn(Response::from($refreshedData));

        // Act
        $result = $this->client->refreshSession();

        // Assert
        expect($result)->toBe($this->client)
            ->and($this->client->getAccessJwt())->toBe($refreshedData['accessJwt'])
            ->and($this->client->getRefreshJwt())->toBe($refreshedData['refreshJwt']);
    });

    it('requires refresh token to refresh session', function (): void {
        // Arrange - ensure no refresh token is set
        $this->client = new Client($this->connector, $this->username);

        // Act & Assert
        expect(fn (): \Bluesky\Client => $this->client->refreshSession())
            ->toThrow(AuthenticationException::class, 'Refresh JWT is required to refresh a session.');
    });

    it('passes tokens to resources', function (): void {
        // Arrange
        $accessToken = 'access-token';
        $this->client = new Client($this->connector, $this->username, $accessToken);

        // Act
        $actor = $this->client->actor();
        $feed = $this->client->feed();

        // Assert - Use reflection to check private properties
        $getToken = function ($resource) {
            $reflection = new ReflectionObject($resource);
            $prop = $reflection->getProperty('accessJwt');

            return $prop->getValue($resource);
        };

        expect($getToken($actor))->toBe($accessToken)
            ->and($getToken($feed))->toBe($accessToken);
    });

    it('passes username to resources', function (): void {
        // Arrange
        $username = 'testuser';
        $client = new Client($this->connector, $username);

        // Act
        $session = $client->session();
        $feed = $client->feed();

        // Assert - Use reflection to check private properties
        $getUsername = function ($resource) {
            $reflection = new ReflectionObject($resource);
            $prop = $reflection->getProperty('username');

            return $prop->getValue($resource);
        };

        expect($getUsername($session))->toBe($username)
            ->and($getUsername($feed))->toBe($username);
    });

    it('provides access to tokens through getters', function (): void {
        // Arrange
        $accessToken = 'access-token';
        $refreshToken = 'refresh-token';
        $client = new Client($this->connector, $this->username, $accessToken, $refreshToken);

        // Assert
        expect($client->getAccessJwt())->toBe($accessToken)
            ->and($client->getRefreshJwt())->toBe($refreshToken);
    });

    it('initializes with constructor parameters', function (): void {
        // Arrange
        $username = 'testuser';
        $accessToken = 'access-token';
        $refreshToken = 'refresh-token';

        // Act
        $client = new Client($this->connector, $username, $accessToken, $refreshToken);

        // Assert
        expect($client->connector)->toBe($this->connector)
            ->and($client->username)->toBe($username)
            ->and($client->getAccessJwt())->toBe($accessToken)
            ->and($client->getRefreshJwt())->toBe($refreshToken);
    });

    it('maintains tokens after session operations', function (): void {
        // Arrange initial session data
        $initialSession = [
            'did' => 'initial-did',
            'handle' => 'initial-handle',
            'email' => 'test@example.com',
            'emailConfirmed' => true,
            'accessJwt' => 'initial-access-token',
            'refreshJwt' => 'initial-refresh-token',
            'active' => true,
        ];

        // Arrange refreshed session data
        $refreshedSession = [
            'did' => 'refreshed-did',
            'handle' => 'refreshed-handle',
            'email' => null,
            'emailConfirmed' => null,
            'accessJwt' => 'refreshed-access-token',
            'refreshJwt' => 'refreshed-refresh-token',
            'active' => true,
        ];

        // Setup mock for initial session
        $this->connector->shouldReceive('requestData')
            ->once()
            ->andReturn(Response::from($initialSession));

        // Act - create new session
        $this->client->newSession('password123');
        $initialAccessToken = $this->client->getAccessJwt();
        $initialRefreshToken = $this->client->getRefreshJwt();

        // Assert initial tokens
        expect($initialAccessToken)->toBe('initial-access-token')
            ->and($initialRefreshToken)->toBe('initial-refresh-token');

        // Setup mock for refresh
        $this->connector->shouldReceive('requestData')
            ->once()
            ->andReturn(Response::from($refreshedSession));

        // Act - refresh session
        $this->client->refreshSession();

        // Assert tokens changed
        expect($this->client->getAccessJwt())
            ->toBe('refreshed-access-token')
            ->not->toBe($initialAccessToken)
            ->and($this->client->getRefreshJwt())
            ->toBe('refreshed-refresh-token')
            ->not->toBe($initialRefreshToken);
    });
});
