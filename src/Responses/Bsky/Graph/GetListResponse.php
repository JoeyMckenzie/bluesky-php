<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Graph;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Profile;
use Bluesky\Types\UserList;
use Override;

/**
 * @implements ResponseContract<array{list: array<key-of<UserList>, mixed>, items: array<int, Profile>, cursor: ?string}>
 */
final readonly class GetListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{list: array<key-of<UserList>, mixed>, items: array<int, Profile>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<key-of<UserList>, mixed>  $list
     * @param  array<int, Profile>  $items
     */
    public function __construct(
        public array $list,
        public array $items,
        public ?string $cursor
    ) {}

    /**
     * @param  array{list: array<key-of<UserList>, mixed>, items: array<int, Profile>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['list'],
            $attributes['items'],
            $attributes['cursor']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'list' => $this->list,
            'items' => $this->items,
            'cursor' => $this->cursor,
        ];
    }
}
