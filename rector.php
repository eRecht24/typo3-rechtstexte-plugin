<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/Resources',
//        __DIR__ . '/public',
    ])
    // uncomment to reach your current PHP version
    ->withTypeCoverageLevel(8)
    ->withSets([
        \Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_81,
        \Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_84,
    ])
    ->withSkip([
        \Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector::class
    ])->withoutParallel();
