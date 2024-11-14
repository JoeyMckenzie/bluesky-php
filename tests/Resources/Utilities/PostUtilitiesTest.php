<?php

declare(strict_types=1);

use Bluesky\Resources\Utilities\PostUtilities;
use Carbon\Carbon;

covers(PostUtilities::class);

describe(PostUtilities::class, function (): void {
    it('handles null datetime by returning current timestamp', function (): void {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $result = PostUtilities::getTimestamp(null);

        expect($result)->toBe($now->toIso8601String());

        Carbon::setTestNow();
    });

    it('handles Carbon instance by returning ISO8601 string', function (): void {
        $carbon = Carbon::create(2024, 1, 1, 12, 0, 0);

        $result = PostUtilities::getTimestamp($carbon);

        expect($result)->toBe('2024-01-01T12:00:00+00:00');
    });

    it('handles DateTime instance by converting to Carbon and returning ISO8601 string', function (): void {
        $dateTime = new DateTime('2024-01-01 12:00:00');

        $result = PostUtilities::getTimestamp($dateTime);

        expect($result)->toBe('2024-01-01T12:00:00+00:00');
    });

    it('specifically handles Carbon vs DateTime instances differently', function (): void {
        // Test with real objects first
        $dateString = '2024-01-01 12:00:00';
        $carbon = Carbon::parse($dateString);
        $dateTime = new DateTime($dateString);

        $carbonResult = PostUtilities::getTimestamp($carbon);
        $dateTimeResult = PostUtilities::getTimestamp($dateTime);
        expect($carbonResult)->toBe($dateTimeResult);

        // Test instance check with a test double
        $testTime = '2024-01-01T12:00:00+00:00';
        $carbonDouble = Mockery::mock(Carbon::class);
        $carbonDouble->shouldReceive('toIso8601String')->once()->andReturn($testTime);
        $carbonDouble->allows('getTimezone')->andReturn(new DateTimeZone('UTC'));
        $carbonDouble->allows('format')->andReturn($dateString);

        expect(PostUtilities::getTimestamp($carbonDouble))->toBe($testTime);
        Mockery::close();
    });
});
