<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\PostThread;
use Override;

/**
 * @implements ResponseContract<array{thread: PostThread, threadgate: ?array{uri: string, cid: string, record: array{lists: array<int, mixed>}}>
 */
final readonly class GetPostThreadResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{thread: PostThread, threadgate: ?array{uri: string, cid: string, record: array{lists: array<int, mixed>}}>
     */
    use ArrayAccessible;

    /**
     * @param  PostThread  $thread
     * @param  null|array{uri: string, cid: string, record: array{lists: array<int, mixed>}}  $threadgate
     */
    public function __construct(
        public array $thread,
        public ?array $threadgate
    ) {}

    public static function from(array $attributes): self
    {
        return new self(
            $attributes['thread'],
            $attributes['threadgate']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'thread' => $this->thread,
            'threadgate' => $this->threadgate,
        ];
    }
}
