<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed\Generator;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Feed;
use Override;

/**
 * @implements ResponseContract<array{view: Feed, isOnline: bool, isValid: bool}>
 */
final readonly class FindResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{view: Feed, isOnline: bool, isValid: bool}>
     */
    use ArrayAccessible;

    /**
     * @param  Feed  $view
     */
    public function __construct(
        public mixed $view,
        public bool $isOnline,
        public bool $isValid
    ) {
        //
    }

    /**
     * @param  array{view: Feed, isOnline: bool, isValid: bool}  $attributes
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
            'isOnline' => $this->isOnline,
            'isValid' => $this->isValid,
        ];
    }
}
