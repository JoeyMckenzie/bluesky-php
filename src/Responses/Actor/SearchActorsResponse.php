<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\ActorProfile;
use Override;

/**
 * @implements ResponseContract<array<int, ActorProfile>>
 */
final readonly class SearchActorsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, ActorProfile>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, ActorProfile>  $data
     */
    private function __construct(
        public array $data,
        public ?string $cursor
    ) {
        //
    }

    /**
     * @param  array{actors: array<int, ActorProfile>, cursor: ?string}  $attributes
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
        return $this->data;
    }
}
