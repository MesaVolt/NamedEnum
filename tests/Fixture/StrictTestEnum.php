<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;


use Mesavolt\Enum\StrictNamedEnum;

abstract class StrictTestEnum extends StrictNamedEnum
{
    public const string FOO = 'bar';

    #[\Override]
    protected static array $VALUE_NAMES = [
        self::FOO => 'FOO NAME'
    ];
}
