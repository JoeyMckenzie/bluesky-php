<?php

declare(strict_types=1);

namespace Bluesky\Responses\App\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{uri: string, cid: string}>
 */
final readonly class CreatePostResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{uri: string, cid: string}>
     */
    use ArrayAccessible;

    private function __construct(
        public string $uri,
        public string $cid,
    ) {
        //
    }

    /**
     * @param  array{uri: string, cid: string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['uri'],
            $attributes['cid'],
        );
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'cid' => $this->cid,
        ];
    }
}
