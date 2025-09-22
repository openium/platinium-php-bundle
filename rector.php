<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/Entity',
        __DIR__ . '/Exception',
        __DIR__ . '/Service',
        __DIR__ . '/Tests',
    ])
    // uncomment to reach your current PHP version
    ->withPhp71Sets()
    ->withPreparedSets(true, true, true)
    ->withAttributesSets(true)
    ->withSets([
        SymfonySetList::SYMFONY_40,
    ])
    ->withTypeCoverageLevel(0);
