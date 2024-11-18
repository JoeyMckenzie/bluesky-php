<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Responses\Feed\GetRepostedByResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\repostedBy;

describe('feed reposted by', function (): void {

    it('can retrieve posts reposted by other users', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getRepostedBy',
            [
                'uri' => 'test-uri',
            ],
            Response::from(repostedBy()),
        );

        // Act
        $result = $client->feed()->getRepostedBy('test-uri');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetRepostedByResponse::class)
            ->repostedBy->not->toBeNull()->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve posts reposted by other users with a cid', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getRepostedBy',
            [
                'uri' => 'test-uri',
                'cid' => 'test-cid',
            ],
            Response::from(repostedBy()),
        );

        // Act
        $result = $client->feed()->getRepostedBy('test-uri', cid: 'test-cid');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetRepostedByResponse::class)
            ->repostedBy->not->toBeNull()->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve posts reposted by other users with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getRepostedBy',
            [
                'uri' => 'test-uri',
                'cursor' => 'test-cursor',
            ],
            Response::from(repostedBy()),
        );

        // Act
        $result = $client->feed()->getRepostedBy('test-uri', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetRepostedByResponse::class)
            ->repostedBy->not->toBeNull()->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });
});
