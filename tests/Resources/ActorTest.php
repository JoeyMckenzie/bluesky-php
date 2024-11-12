<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Responses\Actor\GetPreferencesResponse as PreferencesListResponse;
use Bluesky\Responses\Actor\GetProfileResponse;
use Bluesky\Responses\Actor\GetProfilesResponse as ProfilesListResponse;
use Bluesky\Responses\Actor\GetSuggestionsResponse as SuggestionsListResponse;
use Bluesky\Responses\Actor\SearchActorsResponse as SearchListResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\preferences;
use function Tests\Fixtures\profile;
use function Tests\Fixtures\search;
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
            ->toBeInstanceOf(GetProfileResponse::class)
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
            ->profiles->toBeArray()->toHaveCount(2)
            ->each->toBeInstanceOf(GetProfileResponse::class);
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
            ->preferences->toBeArray()->toHaveCount(4);
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

    it('can search actors with default parameters', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.actor.searchActors',
            [
                'q' => 'php',
                'limit' => 25,
                'cursor' => 0,
            ],
            Response::from(search()),
        );

        // Act
        $result = $client->actor()->searchActors('php');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchListResponse::class)
            ->data->toBeArray()
            ->and(count($result->data))->toBeBetween(1, 25)
            ->and($result->cursor)->toEqual(25);
    })->with();

    it('can search actors with custom parameters', function (): void {
        // Arrange
        $limit = 69;
        $cursor = 42;
        $client = ClientMock::createForGet(
            'app.bsky.actor.searchActors',
            [
                'q' => 'php',
                'limit' => $limit,
                'cursor' => $cursor,
            ],
            Response::from(search($limit, $cursor)),
        );

        // Act
        $result = $client->actor()->searchActors('php', $limit, $cursor);

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchListResponse::class)
            ->data->toBeArray()
            ->and(count($result->data))->toBeBetween(1, $limit)
            ->and($result->cursor)->toEqual($cursor + $limit);
    });

    it('can search actors with default parameters by typeahead', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.actor.searchActorsTypeahead',
            [
                'q' => 'php',
                'limit' => 25,
            ],
            Response::from(search()),
        );

        // Act
        $result = $client->actor()->searchActorsTypeahead('php');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchListResponse::class)
            ->data->toBeArray()
            ->and(count($result->data))->toBeBetween(1, 25);
    })->with();

    it('can search actors with custom parameters by typeahead', function (): void {
        // Arrange
        $limit = 69;
        $cursor = 42;
        $client = ClientMock::createForGet(
            'app.bsky.actor.searchActorsTypeahead',
            [
                'q' => 'php',
                'limit' => $limit,
            ],
            Response::from(search($limit)),
        );

        // Act
        $result = $client->actor()->searchActorsTypeahead('php', $limit);

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchListResponse::class)
            ->data->toBeArray()
            ->and(count($result->data))->toBeBetween(1, $limit);
    });
});
