<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed\Generator;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Feed;
use Override;

/**
 * @implements ResponseContract<array<int, Feed>>
 */
final readonly class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, Feed>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, Feed>  $data
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * @param  array{feeds: array<int, Feed>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['feeds']);
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
