<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources\Bsky;

use Bluesky\Responses\Bsky\Notification\GetUnreadCountResponse;
use Carbon\Carbon;

interface NotificationContract
{
    public function getUnreadCount(bool $priority = false, ?Carbon $seenAt = null): GetUnreadCountResponse;
}
