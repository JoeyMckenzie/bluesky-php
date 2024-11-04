<?php

declare(strict_types=1);

namespace Bluesky\Enums;

/**
 * Represents various HTTP methods utilized for sending requests.
 *
 * @internal
 */
enum HttpMethod: string
{
    case GET = 'GET';

    case POST = 'POST';
}
