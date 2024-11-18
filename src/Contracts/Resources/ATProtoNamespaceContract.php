<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Contracts\Resources\ATProto\ServerContract;

interface ATProtoNamespaceContract
{
    public function server(): ServerContract;
}
