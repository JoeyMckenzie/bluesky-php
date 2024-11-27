<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Graph;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Profile;
use Override;

/**
 * @implements ResponseContract<array{subject: array<key-of<Profile>, mixed>, follows: array<int, Profile>, cursor: ?string}>
 */
final readonly class GetFollowsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{subject: array<key-of<Profile, mixed>>, follows: array<int, Profile>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<key-of<Profile>, mixed>  $subject
     * @param  array<int, Profile>  $follows
     */
    public function __construct(
        public array $subject,
        public array $follows,
        public ?string $cursor
    ) {}

    /**
     * @param  array{subject: array<key-of<Profile>, mixed>, follows: array<int, Profile>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['subject'],
            $attributes['follows'],
            $attributes['cursor']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'subject' => $this->subject,
            'follows' => $this->follows,
            'cursor' => $this->cursor,
        ];
    }
}
