<?php

declare(strict_types=1);

namespace Bluesky\Concerns;

/**
 * @property null|string $accessJwt
 */
trait HasAccessToken
{
    public function getAccessJwt(): ?string
    {
        return $this->accessJwt;
    }
}
