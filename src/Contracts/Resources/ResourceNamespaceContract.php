<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

interface ResourceNamespaceContract
{
    public function getNamespace(): string;
}
