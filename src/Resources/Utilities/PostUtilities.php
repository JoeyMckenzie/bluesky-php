<?php

declare(strict_types=1);

namespace Bluesky\Resources\Utilities;

use Carbon\Carbon;
use DateTime;

final readonly class PostUtilities
{
    public static function getTimestamp(null|Carbon|DateTime $dateTime): string
    {
        // Use current time if null
        if (! $dateTime instanceof DateTime) {
            return Carbon::now()->toIso8601String();
        }

        // Convert any DateTime/Carbon to Carbon and get ISO string
        // We do explicit matching for the mutation test cases here
        $carbon = match ($dateTime::class) {
            Carbon::class => $dateTime,
            default => Carbon::instance($dateTime),
        };

        return $carbon->toIso8601String();
    }
}
