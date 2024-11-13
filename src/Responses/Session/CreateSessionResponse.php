<?php

declare(strict_types=1);

namespace Bluesky\Responses\Session;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{did: string, handle: string, email: ?string, emailConfirmed: ?bool, emailAuthFactor: ?bool, accessJwt: string, refreshJwt: string, active: bool}>
 */
final readonly class CreateSessionResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{did: string, handle: string, email: ?string, emailConfirmed: ?bool, emailAuthFactor: ?bool, accessJwt: string, refreshJwt: string, active: bool}>
     */
    use ArrayAccessible;

    private function __construct(
        public string $did,
        public string $handle,
        public ?string $email,
        public ?bool $emailConfirmed,
        public ?bool $emailAuthFactor,
        public string $accessJwt,
        public string $refreshJwt,
        public bool $active,
    ) {
        //
    }

    /**
     * @param  array{did: string, handle: string, email: ?string, emailConfirmed: ?bool, emailAuthFactor: ?bool, accessJwt: string, refreshJwt: string, active: bool}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['did'],
            $attributes['handle'],
            $attributes['email'] ?? null,
            $attributes['emailConfirmed'] ?? null,
            $attributes['emailAuthFactor'] ?? null,
            $attributes['accessJwt'],
            $attributes['refreshJwt'],
            $attributes['active'],
        );
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'did' => $this->did,
            'handle' => $this->handle,
            'email' => $this->email,
            'emailConfirmed' => $this->emailConfirmed,
            'emailAuthFactor' => $this->emailAuthFactor,
            'accessJwt' => $this->accessJwt,
            'refreshJwt' => $this->refreshJwt,
            'active' => $this->active,
        ];
    }
}
