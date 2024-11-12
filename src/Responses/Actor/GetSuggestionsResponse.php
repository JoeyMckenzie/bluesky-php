<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Suggestion;
use Override;

/**
 * @implements ResponseContract<array<int, Suggestion>>
 */
final readonly class GetSuggestionsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, Suggestion>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, Suggestion>  $data
     */
    private function __construct(
        public array $data,
        public string $cursor
    ) {
        //
    }

    /**
     * @param  array{actors: array<int, Suggestion>, cursor: string}  $attributes
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
