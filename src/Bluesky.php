<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Enums\TargetApi;

final class Bluesky
{
    /**
     * Creates a new client instance with a default session.
     */
    public static function clientWithSession(string $username, string $password, ?Client $client = null): Client
    {
        return $client instanceof \Bluesky\Client // @pest-mutate-ignore
            ? $client->newSession($password)
            : self::client($username)->newSession($password);
    }

    /**
     * Creates a new default client instance.
     */
    public static function client(string $username): Client
    {
        $version = Version::getComposerVersion();

        return self::builder()
            ->withUsername($username)
            ->withHeader('User-Agent', "bluesky-php-client/$version")
            ->build();
    }

    /**
     * Creates a new client builder to configure with custom options.
     */
    public static function builder(): Builder
    {
        return new Builder;
    }

    /**
     * Creates a new public client instance.
     */
    public static function publicClient(): Client
    {
        $version = Version::getComposerVersion();

        return self::builder()
            ->withHeader('User-Agent', "bluesky-php-client/$version")
            ->withTargetApi(TargetApi::PUBLIC)
            ->build();
    }
}
