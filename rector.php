<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/Resources',
        __DIR__ . '/public',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php83: true, php82: true, php84: true, php85: true)
    ->withTypeCoverageLevel(8)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0)
    ->withSkip([
        \Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector::class
    ])
    ->withoutParallel();
