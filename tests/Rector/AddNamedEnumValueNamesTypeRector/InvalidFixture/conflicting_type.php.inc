<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\InvalidFixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    protected static ?array $VALUE_NAMES = [];
}
