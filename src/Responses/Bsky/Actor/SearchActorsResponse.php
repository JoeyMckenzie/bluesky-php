<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Actor;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Profile;
use Override;

/**
 * @implements ResponseContract<array{actors: array<int, Profile>, cursor: ?string}>
 */
final readonly class SearchActorsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{actors: array<int, Profile>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, Profile>  $actors
     */
    private function __construct(
        public array $actors,
        public ?string $cursor
    ) {
        //
    }

    /**
     * @param  array{actors: array<int, Profile>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['actors'], $attributes['cursor']);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'actors' => $this->actors,
            'cursor' => $this->cursor,
        ];
    }
}
