<?php

namespace Mesavolt\Tests\Fixture;


use Mesavolt\Enum\NamedEnum;

abstract class TestEnum extends NamedEnum
{
    public const VALUE_1      = 1;
    public const VALUE_2      = 2;
    public const VALUE_STRING = 'string';

    protected static $VALUE_NAMES = [
        self::VALUE_1 => 'NAME 1',
        self::VALUE_2 => 'NAME 2',
        self::VALUE_STRING => 'NAME STRING'
    ];

    public static $ALTERNATIVE_NAMES = [
        self::VALUE_1 => 'ALTERNATIVE NAME 1',
        self::VALUE_2 => 'ALTERNATIVE NAME 2',
        self::VALUE_STRING => 'ALTERNATIVE NAME STRING',
    ];
}
