<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Exceptions\AuthenticationException;
use Bluesky\Resources\App\Session;
use Bluesky\Responses\Session\CreateSessionResponse;
use Bluesky\ValueObjects\Connector\BaseUri;
use Bluesky\ValueObjects\Connector\Headers;
use Bluesky\ValueObjects\Connector\QueryParams;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Pest\Faker\fake;
use function Tests\Fixtures\refreshedSession;
use function Tests\Fixtures\session;

covers(Session::class);

describe(Session::class, function (): void {
    beforeEach(function (): void {
        ClientMock::reset();
    });

    it('requires non-empty password for session creation', function (): void {
        // Arrange
        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.createSession',
            [
                'identifier' => 'username',
                'password' => 'test',
            ],
            Response::from(session()),
            'requestData',
            shouldCall: false
        );

        // Assert empty string throws
        expect(fn (): CreateSessionResponse => $client->session()->createSession(''))
            ->toThrow(AuthenticationException::class, 'Password cannot be empty.');

        // Assert mutation string does not throw (opposite of empty)
        $nonEmptyPassword = 'password';
        expect($nonEmptyPassword)->not->toBe('')
            ->and(fn (): CreateSessionResponse => $client->session()->createSession($nonEmptyPassword))
            ->not->toThrow(AuthenticationException::class);
    });

    it('verifies both identifier and password are required and correct', function (): void {
        // Arrange
        $username = 'username';
        $password = 'password';

        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.createSession',
            [
                'identifier' => $username,
                'password' => $password,
            ],
            Response::from(session()),
            'requestData',
        );

        // Act
        $result = $client->session()->createSession($password);

        // Get request payload
        $lastPayload = ClientMock::getLastPayload();
        $request = $lastPayload?->toRequest(
            BaseUri::from('test'),
            Headers::create(),
            QueryParams::create()
        );

        $body = json_decode($request?->getBody()->getContents() ?? '[]', true);

        // Assert array structure
        expect($body)
            ->toBeArray()
            ->toHaveCount(2)
            ->toHaveKey('identifier')
            ->toHaveKey('password')
            ->and($body['identifier'])->toBe($username)
            ->and($body['password'])->toBe($password)
            ->and($body)->not->toBe(['password' => $password])
            ->and($body)->not->toBe(['identifier' => $username]);
    });

    it('requires non-empty refresh token', function (): void {
        // Arrange
        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.refreshSession',
            [],
            Response::from(refreshedSession()),
            'requestData',
            shouldCall: false
        );

        // Assert empty string throws
        expect(fn (): CreateSessionResponse => $client->session()->refreshSession(''))
            ->toThrow(AuthenticationException::class, 'Refresh token cannot be empty.');

        // Assert mutation string does not throw (opposite of empty)
        $nonEmptyToken = 'token';
        expect($nonEmptyToken)->not->toBe('')
            ->and(fn (): CreateSessionResponse => $client->session()->refreshSession($nonEmptyToken))
            ->not->toThrow(AuthenticationException::class);
    });

    it('verifies refresh session payload body inclusion', function (): void {
        // Arrange
        $session = refreshedSession();
        $refreshToken = fake()->uuid();

        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.refreshSession',
            [],
            Response::from($session),
            'requestData',
            additionalHeaders: [
                'Authorization' => 'Bearer '.$refreshToken,
            ],
        );

        // Act
        $client->session()->refreshSession($refreshToken);

        // Get request payload
        $lastPayload = ClientMock::getLastPayload();
        $request = $lastPayload?->toRequest(
            BaseUri::from('test'),
            Headers::create(),
            QueryParams::create()
        );

        // Assert body inclusion flag
        expect($lastPayload?->includeBody)->toBeFalse();

        // Verify request body state when includeBody is false
        $body = $request?->getBody();
        expect($body)->not->toBeEmpty()
            ->and($body->getContents())->toBe('')
            ->and($body->getSize())->toBe(0)
            ->and($lastPayload?->includeBody)->not->toBeTrue()
            ->and($body->getSize())->not->toBeGreaterThan(0);
    });

    it('verifies refresh session authorization header format', function (): void {
        // Arrange
        $session = refreshedSession();
        $refreshToken = fake()->uuid();
        $bearerPrefix = 'Bearer ';
        $expectedHeader = $bearerPrefix.$refreshToken;

        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.refreshSession',
            [],
            Response::from($session),
            'requestData',
            additionalHeaders: [
                'Authorization' => $expectedHeader,
            ],
        );

        // Act
        $client->session()->refreshSession($refreshToken);

        // Get request for validation
        $lastPayload = ClientMock::getLastPayload();
        $request = $lastPayload?->toRequest(
            BaseUri::from('test'),
            Headers::create(),
            QueryParams::create()
        );

        $headers = $request?->getHeaders();
        $authHeader = $request?->getHeaderLine('Authorization');

        // Verify Authorization header exists and is required
        expect($headers)
            ->toHaveKey('Authorization')
            ->and($request?->hasHeader('Authorization'))->toBeTrue()
            ->and($request?->getHeader('Authorization'))->toHaveCount(1)
            ->and($authHeader)
            ->toBe($expectedHeader)
            ->toStartWith($bearerPrefix)
            ->toEndWith($refreshToken)
            ->not->toBe($refreshToken) // ConcatRemoveLeft
            ->not->toBe($bearerPrefix) // ConcatRemoveRight
            ->not->toBe($refreshToken.$bearerPrefix)
            ->and(substr_count($authHeader, $bearerPrefix))->toBe(1)
            ->and(substr_count($authHeader, $refreshToken))->toBe(1)
            ->and(strpos($authHeader, $bearerPrefix))->toBe(0)
            ->and(strpos($authHeader, $refreshToken))->toBe(strlen($bearerPrefix))
            ->and(strlen($authHeader))->toBe(strlen($bearerPrefix) + strlen($refreshToken));
    });

    it('requires authorization header in refresh session request', function (): void {
        // Arrange
        $session = refreshedSession();
        $refreshToken = fake()->uuid();
        $expectedHeader = ['Authorization' => 'Bearer '.$refreshToken];

        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.refreshSession',
            [],
            Response::from($session),
            'requestData',
            additionalHeaders: $expectedHeader,
        );

        // Act
        $client->session()->refreshSession($refreshToken);

        // Get request for validation
        $lastPayload = ClientMock::getLastPayload();
        $request = $lastPayload?->toRequest(
            BaseUri::from('test'),
            Headers::create(),
            QueryParams::create()
        );

        $headers = $request?->getHeaders();

        // Verify Authorization header exists and cannot be removed
        expect($headers)
            ->toHaveKey('Authorization')
            ->and($request?->hasHeader('Authorization'))->toBeTrue()
            ->and($request?->getHeader('Authorization'))->toHaveCount(1)
            ->and($request?->getHeaderLine('Authorization'))->toBe($expectedHeader['Authorization'])
            ->and(array_key_exists('Authorization', $headers ?? []))->toBeTrue();
    });
});
