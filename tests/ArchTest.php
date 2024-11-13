<?php

declare(strict_types=1);

namespace Tests;

use Bluesky\Contracts\ResponseContract;

arch('All source files are strictly typed')
    ->expect('Bluesky\\')
    ->toUseStrictTypes();

arch('All tests files are strictly typed')
    ->expect('Tests\\')
    ->toUseStrictTypes();

arch('Value objects should be immutable')
    ->expect('Bluesky\\ValueObjects\\')
    ->toBeFinal()
    ->and('Bluesky\\ValueObjects\\')
    ->toBeReadonly();

arch('Responses should be immutable and implement response contracts')
    ->expect('Bluesky\\Responses\\')
    ->classes()
    ->toBeFinal()
    ->and('Bluesky\\Responses\\')
    ->classes()
    ->toBeReadonly()
    ->and('Bluesky\\Responses\\')
    ->classes()
    ->toImplement(ResponseContract::class);

arch('Resources should be immutable')
    ->expect('Bluesky\\Resources\\')
    ->classes()
    ->toBeFinal()
    ->toBeReadonly();

arch('Contracts should be abstract')
    ->expect('Bluesky\\Contracts\\')
    ->toBeInterfaces();

arch('All Enums are backed')
    ->expect('Bluesky\\Enums\\')
    ->toBeStringBackedEnums();
