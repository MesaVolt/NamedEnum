<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    public static $ALTERNATIVE_NAMES = ['pending' => 'Pending'];
    protected static $VALUE_NAMES_EXTRA = [];
}
