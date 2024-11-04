<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Bluesky;

describe('Actor resource', function (): void {
    beforeAll(function (): void {
        $username = (string) getenv('BLUESKY_USERNAME');
        $password = (string) getenv('BLUESKY_PASSWORD');
        $this->client = Bluesky::client();
        $this->client->session()->createSession($username, $password);
    });

    it('can retrieve a profile given a did', function (): void {
        // Arrange
        $did = (string) getenv('BLUESKY_DID');

        // Act
        $result = $this->client->actor()->getProfile($did);

        // Assert
        expect($result)->not->toBeNull();
    });
});
