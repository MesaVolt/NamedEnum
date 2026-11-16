<?php

declare(strict_types=1);

use Mesavolt\Rector\AddNamedEnumValueNamesTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([AddNamedEnumValueNamesTypeRector::class]);
