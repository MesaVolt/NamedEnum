<?php

namespace Mesavolt\Tests\Fixture;


use Mesavolt\Enum\NamedEnum;

abstract class IncompleteNamedEnum extends NamedEnum
{
    public const VALUE_1 = 1;
    public const VALUE_2 = 2;
    public const VALUE_3 = 3;

    protected static $VALUE_NAMES = [
        self::VALUE_1 => 'This is 1',
    ];
}
