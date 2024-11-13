<?php

declare(strict_types=1);

namespace Bluesky\Concerns;

/**
 * @property string $username
 */
trait HasUserContext
{
    public function getUsername(): string
    {
        return $this->username;
    }
}
