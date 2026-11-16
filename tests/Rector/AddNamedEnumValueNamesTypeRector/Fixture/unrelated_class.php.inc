<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

abstract class Status
{
    protected static $VALUE_NAMES = ['pending' => 'Pending'];
}
