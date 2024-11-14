<?php

declare(strict_types=1);

namespace Bluesky\ValueObjects\Connector;

use Override;
use Stringable;

/**
 * A value object for representing the base URI on all requests.
 *
 * @internal
 */
final readonly class BaseUri implements Stringable
{
    /**
     * Creates a new Base URI value object.
     */
    private function __construct(private string $baseUri) {}

    #[Override]
    public function __toString(): string
    {
        $uri = $this->baseUri;

        // Add protocol if missing
        if (! str_starts_with($uri, 'http://') && ! str_starts_with($uri, 'https://')) {
            $uri = "https://$uri";
        }

        // Ensure single trailing slash
        return rtrim($uri, '/').'/';
    }

    /**
     * Creates a new Base URI value object.
     */
    public static function from(string $baseUri): self
    {
        return new self($baseUri);
    }
}
