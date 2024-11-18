<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Notification;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{count: int}>
 */
final readonly class GetUnreadCountResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{count: int}>
     */
    use ArrayAccessible;

    public function __construct(public int $count)
    {
        //
    }

    /**
     * @param  array{count: int}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['count']);
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'count' => $this->count,
        ];
    }
}
