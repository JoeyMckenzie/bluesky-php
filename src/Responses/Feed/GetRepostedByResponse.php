<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Profile;
use Override;

/**
 * @implements ResponseContract<array{uri: string, cid: ?string, cursor: ?string, repostedBy: array<int, Profile>}>
 */
final readonly class GetRepostedByResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{uri: string, cid: ?string, cursor: ?string, repostedBy: array<int, Profile>}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, Profile>  $repostedBy
     */
    public function __construct(
        public string $uri,
        public ?string $cid,
        public ?string $cursor,
        public array $repostedBy,
    ) {
        //
    }

    /**
     * @param  array{uri: string, cid: ?string, cursor: ?string, repostedBy: array<int, Profile>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['uri'],
            $attributes['cid'],
            $attributes['cursor'],
            $attributes['repostedBy'],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'cid' => $this->cid,
            'cursor' => $this->cursor,
            'repostedBy' => $this->repostedBy,
        ];
    }
}
