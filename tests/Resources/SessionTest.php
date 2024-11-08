<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Session\CreateResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Pest\Faker\fake;
use function Tests\Fixtures\refreshedSession;
use function Tests\Fixtures\session;

describe('Session resource', function (): void {
    it('can create sessions', function (): void {
        // Arrange
        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.createSession',
            [
                'identifier' => 'username',
                'password' => 'password',
            ],
            Response::from(session()),
            'requestData',
        );

        // Act
        $result = $client->session()->createSession('password');

        // Assert, spot check a few properties
        expect($result)->not->toBeNull()
            ->and($result)->toBeInstanceOf(CreateResponse::class)
            ->accessJwt->not->toBeNull()
            ->refreshJwt->not->toBeNull()
            ->email->not->toBeNull()
            ->emailConfirmed->not->toBeNull()
            ->emailConfirmed->not->toBeNull()
            ->did->not->toBeNull();
    });

    it('can refresh sessions', function (): void {
        // Arrange
        $refreshToken = fake()->uuid();
        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.server.refreshSession',
            [],
            Response::from(refreshedSession()),
            'requestData',
            additionalHeaders: [
                'Authorization' => 'Bearer '.$refreshToken,
            ],
        );

        // Act
        $result = $client->session()->refreshSession($refreshToken);

        // Assert, spot check a few properties
        expect($result)->not->toBeNull()
            ->and($result)->toBeInstanceOf(CreateResponse::class)
            ->accessJwt->not->toBeNull()
            ->refreshJwt->not->toBeNull()
            ->email->toBeNull()
            ->emailConfirmed->toBeNull()
            ->emailAuthFactor->toBeNull()
            ->did->not->toBeNull();
    });
});
