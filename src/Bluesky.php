<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Exceptions\ErrorException;

final class Bluesky
{
    private static ?Bluesky $instance = null;

    private static ?Client $client = null;

    private function __construct(Client $client)
    {
        Bluesky::$client = $client;
    }

    /**
     * Creates a new default client instance.
     */
    public static function client(): Client
    {
        $version = Version::getComposerVersion();
        $client = self::builder()
            ->withHeader('User-Agent', "bluesky-php-client/$version")
            ->build();

        Bluesky::$instance = new self($client);

        return self::$client ?? throw new ErrorException('Unable to create Bluesky client.');
    }

    /**
     * Creates a new client builder to configure with custom options.
     */
    public static function builder(): Builder
    {
        return new Builder;
    }
}
