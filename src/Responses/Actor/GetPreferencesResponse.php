<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{preferences: list<array{"$type": string}&array<string, mixed>>}>
 */
final readonly class GetPreferencesResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{preferences: list<array{"$type": string}&array<string, mixed>>}>
     */
    use ArrayAccessible;

    /**
     * @param  list<array{"$type": string}&array<string, mixed>>  $preferences
     */
    private function __construct(public array $preferences)
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
        return [
            'preferences' => $this->preferences,
        ];
    }
}
