<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Session\CreateResponse;
use Bluesky\ValueObjects\Connector\Response;

use function Tests\mockClient;

describe('Session resource', function (): void {
    $session = fn(): array => [
        'did' => 'did:plc:abc123',
        'handle' => 'joeymckenzie.bsky.social',
        'email' => 'test@gmail.com',
        'emailConfirmed' => true,
        'emailAuthFactor' => false,
        'accessJwt' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciIsImlhdCI6MTY5ODc2MjAwMH0.RuopNsX-kPK2zFQM85mKtQMZCUFNKcPtJPiYf1v7HCI',
        'refreshJwt' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciIsImlhdCI6MTY5ODc2MjAwMH0.RuopNsX-kPK2zFQM85mKtQMZCUFNKcPtJPiYf1v7HCI',
        'active' => true,
    ];

    it('can create sessions', function () use ($session): void {
        // Arrange
        $client = mockClient(
            HttpMethod::POST,
            'com.atproto.server.createSession',
            [
                'identifier' => 'username',
                'password' => 'password',
            ],
            Response::from($session()),
        );

        // Act
        $result = $client->session()->createSession('username', 'password');

        // Assert, spot check a few properties
        expect($result)->not->toBeNull()
            ->and($result)->toBeInstanceOf(CreateResponse::class)
            ->accessJwt->not->toBeNull()
            ->refreshJwt->not->toBeNull()
            ->active->toBeTrue();
    });
});
