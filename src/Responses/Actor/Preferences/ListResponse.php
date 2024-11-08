<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor\Preferences;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<list<array{"$type": string}&array<string, mixed>>>
 */
final readonly class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<list<array{"$type": string}&array<string, mixed>>>
     */
    use ArrayAccessible;

    /**
     * @param  list<array{"$type": string}&array<string, mixed>>  $data
     */
    private function __construct(public array $data)
    {
        //
    }

    /**
     * @param  array{preferences: list<array{"$type": string}&array<string, mixed>>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['preferences']);
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
