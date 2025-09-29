<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/Entity',
        __DIR__ . '/Exception',
        __DIR__ . '/Service',
        __DIR__ . '/Tests',
        __DIR__ . '/*.php',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php81: true)
    ->withAttributesSets(true)
    ->withSets([
        SymfonySetList::SYMFONY_64,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE
    ])
    ->withTypeCoverageLevel(0);
