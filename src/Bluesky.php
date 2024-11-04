<?php

declare(strict_types=1);

namespace Bluesky;

final class Bluesky
{
    /**
     * Creates a new default client instance.
     */
    public static function client(): Client
    {
        $version = Version::getComposerVersion();

        return self::builder()
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
}
