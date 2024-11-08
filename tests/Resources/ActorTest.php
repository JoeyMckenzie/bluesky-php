<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Responses\Actor\Preferences\ListResponse as PreferencesListResponse;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\Responses\Actor\Profile\ListResponse as ProfilesListResponse;
use Bluesky\Responses\Actor\Suggestions\ListResponse as SuggestionsListResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\preferences;
use function Tests\Fixtures\profile;
use function Tests\Fixtures\suggestions;

describe('Actor resource', function (): void {

    it('can retrieve a profile given a did or handle', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.actor.getProfile',
            ['actor' => 'test'],
            Response::from(profile()),
        );

        // Act
        $result = $client->actor()->getProfile('test');

        // Assert
        expect($result)
            ->toBeInstanceOf(FindResponse::class)
            ->did->not->toBeNull()
            ->description->not->toBeNull()
            ->avatar->not->toBeNull();
    });

    it('can retrieve profile given a list of dids or handles', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.actor.getProfiles',
            [
                'actors' => [
                    'user1',
                    'user2',
                ],
            ],
            Response::from([
                'profiles' => [
                    profile(),
                    profile(),
                ],
            ]),
        );

        // Act
        $result = $client->actor()->getProfiles(['user1', 'user2']);

        // Assert
        expect($result)
            ->toBeInstanceOf(ProfilesListResponse::class)
            ->data->toBeArray()->toHaveCount(2)
            ->each->toBeInstanceOf(FindResponse::class);
    });

    it('can retrieve preferences', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.actor.getPreferences',
            [],
            Response::from(preferences()),
        );

        // Act
        $result = $client->actor()->getPreferences();

        // Assert
        expect($result)
            ->toBeInstanceOf(PreferencesListResponse::class)
            ->data->toBeArray()->toHaveCount(4);
    });

    it('can update preferences', function (): void {
        // Arrange
        $preferences = preferences();
        $client = ClientMock::createForPost(
            'app.bsky.actor.putPreferences',
            $preferences,
            null,
        );

        // Act & Assert the call went through as expected
        $client->actor()->putPreferences($preferences['preferences']);
    });

    it('can retrieve suggestions', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.actor.getSuggestions',
            [
                'limit' => 50,
                'cursor' => 0,
            ],
            Response::from(suggestions()),
        );

        // Act
        $result = $client->actor()->getSuggestions();

        // Assert
        expect($result)
            ->toBeInstanceOf(SuggestionsListResponse::class)
            ->data->toBeArray()
            ->and(count($result->data))->toBeBetween(1, 50)
            ->and($result->cursor)->toEqual(50);
    });

    it('can retrieve suggestions with limit and cursor parameters', function (): void {
        // Arrange
        $limit = 42;
        $cursor = 69;
        $client = ClientMock::createForGet(
            'app.bsky.actor.getSuggestions',
            [
                'limit' => $limit,
                'cursor' => $cursor,
            ],
            Response::from(suggestions($limit, $cursor)),
        );

        // Act
        $result = $client->actor()->getSuggestions($limit, $cursor);

        // Assert
        expect($result)
            ->toBeInstanceOf(SuggestionsListResponse::class)
            ->data->toBeArray()
            ->and(count($result->data))->toEqual($limit)
            ->and(intval($result->cursor))->toBe($limit + $cursor);
    });
});
