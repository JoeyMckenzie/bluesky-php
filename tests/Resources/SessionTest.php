<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Bluesky;

it('can create sessions', function (): void {
    // Arrange
    $username = (string) getenv('BLUESKY_USERNAME');
    $password = (string) getenv('BLUESKY_PASSWORD');
    $client = Bluesky::client();

    // Act
    $result = $client->session()->createSession($username, $password);

    // Assert, spot check a few properties
    expect($result)->not->toBeNull()
        ->and($result->accessJwt)->not->toBeNull()
        ->and($result->refreshJwt)->not->toBeNull()
        ->and($result->active)->toBeTrue();
});
