<?php

declare(strict_types=1);

namespace Bluesky\Enums;

enum TargetApi: string
{
    case PUBLIC = 'Public';

    case AUTHENTICATED = 'Authenticated';
}
