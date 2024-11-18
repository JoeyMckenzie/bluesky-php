<?php

declare(strict_types=1);

namespace Tests;

foreach (glob(__DIR__.'/Fixtures/**/*Fixture.php') as $fixture) {
    require_once $fixture;
}

foreach (glob(__DIR__.'/Mocks/*Mock.php') as $mock) {
    require_once $mock;
}
