<?php

namespace Mesavolt\Tests\Fixture;


use Mesavolt\Enum\StrictNamedEnum;

abstract class StrictTestEnum extends StrictNamedEnum
{
    public const FOO = 'bar';

    protected static $VALUE_NAMES = [
        self::FOO => 'FOO NAME'
    ];
}
