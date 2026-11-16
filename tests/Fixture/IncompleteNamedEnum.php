<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;


use Mesavolt\Enum\NamedEnum;

abstract class IncompleteNamedEnum extends NamedEnum
{
    public const int VALUE_1 = 1;
    public const int VALUE_2 = 2;
    public const int VALUE_3 = 3;

    protected static array $VALUE_NAMES = [
        self::VALUE_1 => 'This is 1',
    ];
}
