<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor\Preferences;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array<int, array{"$type": string, birthDate?: string, tags?: array<int, string>, items?: array<int, array{type: string, value: string, pinned: bool, id: string}>, nuxs?: array<int, array{id: string, completed: bool}>}>>
 */
final class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, array{"$type": string, birthDate?: string, tags?: array<int, string>, items?: array<int, array{type: string, value: string, pinned: bool, id: string}>, nuxs?: array<int, array{id: string, completed: bool}>}>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, array{"$type": string, birthDate?: string, tags?: array<int, string>, items?: array<int, array{type: string, value: string, pinned: bool, id: string}>, nuxs?: array<int, array{id: string, completed: bool}>}>  $data
     */
    private function __construct(public array $data)
    {
        //
    }

    /**
     * @param  array{preferences: array<int, array{"$type": string, birthDate?: string, tags?: array<int, string>, items?: array<int, array{type: string, value: string, pinned: bool, id: string}>, nuxs?: array<int, array{id: string, completed: bool}>}>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['preferences']);
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->data;
    }
}
