<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Graph;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Profile;
use Override;

/**
 * @implements ResponseContract<array{subject: Profile, followers: array<int, Profile>, cursor: ?string}>
 */
final readonly class GetFollowersResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{subject: Profile, followers: array<int, Profile>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<Profile>  $subject
     * @param  array<int, Profile>  $followers
     */
    public function __construct(
        public array $subject,
        public array $followers,
        public ?string $cursor
    ) {}

    /**
     * @param  array{subject: Profile, followers: array<int, Profile>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['subject'],
            $attributes['followers'],
            $attributes['cursor']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'subject' => $this->subject,
            'followers' => $this->followers,
            'cursor' => $this->cursor,
        ];
    }
}
