<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\FeedGenerator;
use Override;

/**
 * @implements ResponseContract<array{view: FeedGenerator, isOnline: bool, isValid: bool}>
 */
final readonly class GetFeedGeneratorResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{view: FeedGenerator, isOnline: bool, isValid: bool}>
     */
    use ArrayAccessible;

    /**
     * @param  FeedGenerator  $view
     */
    public function __construct(
        public mixed $view,
        public bool $isOnline,
        public bool $isValid
    ) {
        //
    }

    /**
     * @param  array{view: FeedGenerator, isOnline: bool, isValid: bool}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['view'],
            $attributes['isOnline'],
            $attributes['isValid']
        );
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'view' => $this->view,
            'isValid' => $this->isValid,
            'isOnline' => $this->isOnline,
        ];
    }
}
