<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Resources\Session;
use Bluesky\Responses\Session\CreateSessionResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Pest\Faker\fake;
use function Tests\Fixtures\refreshedSession;
use function Tests\Fixtures\session;

// TODO: Need to think of a clever way to validate request headers to get these mutation tests passing
// covers(Session::class);

describe(Session::class, function (): void {
    it('can create sessions', function (): void {
        // Arrange
        $session = session();
        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.createSession',
            [
                'identifier' => 'username',
                'password' => 'password',
            ],
            Response::from($session),
            'requestData',
        );

        // Act
        $result = $client->session()->createSession('password');

        // Assert, spot check a few properties
        expect($result)->not->toBeNull()
            ->and($result)->toBeInstanceOf(CreateSessionResponse::class)
            ->accessJwt->toBe($session['accessJwt'])
            ->refreshJwt->toBe($session['refreshJwt'])
            ->email->toBe($session['email'])
            ->emailConfirmed->toBe($session['emailConfirmed'])
            ->emailAuthFactor->toBe($session['emailAuthFactor'])
            ->active->toBe($session['active'])
            ->did->toBe($session['did']);
    });

    it('can refresh sessions', function (): void {
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
        $result = $client->session()->refreshSession($refreshToken);

        // Assert, spot check a few properties
        expect($result)->not->toBeNull()
            ->and($result)->toBeInstanceOf(CreateSessionResponse::class)
            ->accessJwt->toBe($session['accessJwt'])
            ->refreshJwt->toBe($session['refreshJwt'])
            ->email->toBeNull()
            ->emailConfirmed->toBeNull()
            ->emailAuthFactor->toBeNull()
            ->active->toBe($session['active'])
            ->did->toBe($session['did']);
    });
});
