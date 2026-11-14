<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets(php85: true)
    ->withComposerBased(phpunit: true)
    ->withAttributesSets(phpunit: true)
    ->withPreparedSets(
        codeQuality: true,
        typeDeclarations: true,
    )
    ->withImportNames(importShortClasses: false);
