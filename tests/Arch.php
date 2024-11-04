<?php

declare(strict_types=1);

namespace Tests;

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

arch('Responses should be immutable')
    ->expect('Bluesky\\Responses\\Breweries\\')
    ->toBeFinal()
    ->and('Bluesky\\Responses\\Breweries\\')
    ->toBeReadonly();

arch('Contracts should be abstract')
    ->expect('Bluesky\\Contracts\\')
    ->toBeInterfaces();

arch('All Enums are backed')
    ->expect('Bluesky\\Enums\\')
    ->toBeStringBackedEnums();
