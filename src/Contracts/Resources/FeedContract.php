<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Feed\CreateResponse;
use Carbon\Carbon;

interface FeedContract
{
    public function post(string $text, ?Carbon $createdAt = null): CreateResponse;
}
